# Econopapi Plotly Figure

Bloque dinámico `econopapi/plotly-figure` para publicar figuras de Plotly sin guardar datos ni definiciones de gráficos en el tema.

## Flujo de trabajo

1. El análisis en Python produce una figura Plotly y el DataFrame que representa.
2. Un exportador genera un único manifiesto JSON con figura, tabla y procedencia.
3. En Gutenberg, inserta **Econopapi Plotly Figure** y sube el JSON en **Fuente de la figura**.
4. Ajusta, si hace falta, los textos editoriales, la altura y el estado inicial de la tabla.

El editor lee `meta` y muestra sus valores directamente en los controles. Esos valores no se duplican como atributos del bloque hasta que los modificas. Al reemplazar el manifiesto se descartan los overrides editoriales del archivo anterior para mostrar los metadatos del nuevo JSON.

El JSON puede reutilizarse en varias entradas. La tabla se imprime en PHP y permanece disponible aunque falle JavaScript o Plotly.

## Por qué JSON y no HTML

`fig.write_html()` es útil para compartir o archivar una figura independiente, pero no como entrada de este bloque: el HTML autocontenido incluye otra copia de Plotly, no ofrece un contrato de accesibilidad y dificulta aplicar el tema claro/oscuro de Econopapi.

La figura usa el equivalente de `json.loads(fig.to_json())`. El manifiesto añade los datos tabulares y la procedencia que no forman parte del JSON nativo de Plotly.

## Contrato del manifiesto

Campos obligatorios:

- `schemaVersion`: actualmente `1`.
- `meta.title` y `meta.alt`.
- `meta.sources`: al menos una fuente con `label`; `url` es opcional.
- `figure.data` y `figure.layout`: objetos nativos de Plotly.
- `table.columns`: columnas con `key`, `label` y, opcionalmente, `type` y `unit`.
- `table.rows`: un objeto por fila; sus celdas deben ser escalares o `null`.

Campos opcionales:

- `meta.kicker`, `description`, `unit`, `methodology`, `analysis`, `generatedAt` y `note`.
- `figure.frames` para animaciones.
- `config` para opciones JSON de Plotly. El bloque siempre fuerza `responsive: true` y `displaylogo: false`.
- `table.caption`.

Tipos de columna admitidos: `string`, `number`, `boolean`, `date` y `datetime`.

### Ejemplo mínimo

```json
{
  "schemaVersion": 1,
  "meta": {
    "title": "Inversión pública por año",
    "kicker": "FINANZAS PÚBLICAS",
    "description": "Valores constantes para el periodo analizado.",
    "alt": "Gráfico de barras de inversión pública en 2024 y 2025; aumenta de 10 a 12.",
    "unit": "miles de millones de pesos",
    "sources": [
      {
        "label": "Cálculos propios con datos de la fuente estadística",
        "url": "https://example.org/dataset"
      }
    ],
    "methodology": {
      "label": "Nota metodológica",
      "url": "https://econopapi.com/metodologia/"
    },
    "analysis": {
      "repository": "https://github.com/econopapi/proyecto",
      "commit": "1363af84d5089ca4260645012dcb34ccc93d4c6c",
      "path": "analysis/inversion.py"
    },
    "generatedAt": "2026-10-30T18:00:00Z"
  },
  "figure": {
    "data": [
      {
        "type": "bar",
        "name": "Inversión",
        "x": ["2024", "2025"],
        "y": [10, 12],
        "hovertemplate": "%{x}: %{y}<extra></extra>"
      }
    ],
    "layout": {
      "xaxis": { "title": "Año" },
      "yaxis": { "title": "Miles de millones de pesos" }
    },
    "frames": []
  },
  "config": {
    "displayModeBar": false
  },
  "table": {
    "caption": "Datos representados en la figura",
    "columns": [
      { "key": "year", "label": "Año", "type": "string" },
      { "key": "investment", "label": "Inversión", "type": "number", "unit": "miles de millones de pesos" }
    ],
    "rows": [
      { "year": "2024", "investment": 10 },
      { "year": "2025", "investment": 12 }
    ]
  }
}
```

## Exportador de referencia para Python

El exportador debe vivir junto al análisis o en el paquete de utilidades del proyecto de investigación, no dentro del tema de WordPress.

```python
import json
from pathlib import Path

import pandas as pd
from plotly.utils import PlotlyJSONEncoder


def _column_type(series: pd.Series) -> str:
    if pd.api.types.is_bool_dtype(series):
        return "boolean"
    if pd.api.types.is_numeric_dtype(series):
        return "number"
    if pd.api.types.is_datetime64_any_dtype(series):
        return "datetime"
    return "string"


def export_econopapi_figure(
    fig,
    dataframe: pd.DataFrame,
    metadata: dict,
    path: str | Path,
    *,
    columns: list[dict] | None = None,
    table_caption: str = "Datos representados en la figura",
    config: dict | None = None,
) -> Path:
    """Exporta figura, datos visibles y metadatos como manifiesto v1."""
    if columns is None:
        columns = [
            {
                "key": str(column),
                "label": str(column),
                "type": _column_type(dataframe[column]),
            }
            for column in dataframe.columns
        ]

    # Pandas normaliza fechas y valores faltantes antes de crear las filas.
    rows = json.loads(
        dataframe.to_json(orient="records", date_format="iso")
    )

    manifest = {
        "schemaVersion": 1,
        "meta": metadata,
        "figure": json.loads(fig.to_json()),
        "table": {
            "caption": table_caption,
            "columns": columns,
            "rows": rows,
        },
    }
    if config is not None:
        manifest["config"] = config

    output = Path(path)
    output.parent.mkdir(parents=True, exist_ok=True)
    output.write_text(
        json.dumps(
            manifest,
            cls=PlotlyJSONEncoder,
            ensure_ascii=False,
            allow_nan=False,
            indent=2,
        )
        + "\n",
        encoding="utf-8",
    )
    return output
```

Ejemplo de uso:

```python
metadata = {
    "title": "Inversión pública por año",
    "alt": "Barras de inversión pública en 2024 y 2025.",
    "sources": [{"label": "Fuente estadística", "url": "https://example.org"}],
    "methodology": {"label": "Metodología", "url": "https://example.org/metodo"},
    "analysis": {
        "repository": "https://github.com/econopapi/proyecto",
        "commit": "1363af84d5089ca4260645012dcb34ccc93d4c6c",
        "path": "analysis/inversion.py",
    },
    "generatedAt": "2026-10-30T18:00:00Z",
}

export_econopapi_figure(fig, tabla, metadata, "exports/inversion.json")
```

## Reglas para conservar la identidad visual

- No fijar fuentes, fondos ni colores cosméticos en Python.
- Reservar colores explícitos para codificaciones con significado analítico.
- El bloque proporciona la paleta categórica, tipografía, ejes, retículas, leyendas y hovers para light/dark.
- Mantener los valores numéricos como números, no como cadenas preformateadas.
- La tabla debe corresponder a los datos realmente graficados. Si el dataset crudo es distinto o más extenso, documentarlo y ofrecerlo aparte.

## Límites y seguridad

- Tamaño máximo del manifiesto: 5 MB.
- La subida JSON está habilitada sólo para administradores.
- Se validan estructura, fuentes, columnas y celdas antes del render.
- Los metadatos y valores de tabla se escapan en PHP; el payload se codifica para impedir cierres de `script`.
- Plotly.js 2.35.2 se sirve localmente y se carga una sola vez cuando el bloque está presente.
