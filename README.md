# Level Test Form (Plugin de WordPress)

Este plugin ha sido desarrollado a medida para habilitar un test de evaluación del nivel de español de estudiantes en el sitio web de "Todo en Español". A través de 30 preguntas de selección múltiple predefinidas, clasifica a los estudiantes en niveles desde **A1 hasta C1**, solicita su correo para mostrarle un gancho de ventas y registra sus resultados y niveles en el panel de WordPress del profesor.

## 🚀 Características Principales

- **Motor de Evaluación**: 30 preguntas fijas agrupadas por niveles de dificultad, ponderadas a 1 punto cada una. Evalúa y determina en tiempo real el nivel (A1, A2, B1, B2, C1) correspondiente.
- **Formulario Interactivo Público**: UI/UX interactiva usando AJAX, CSS con variables y transiciones enriquecidas (además de la tipografía **Comfortaa** y **PT Serif Caption**), que no requiere recargar la página para avanzar entre bloques. 
- **Shortcode Inyectable**: Simplemente utiliza `[test_de_nivel]` en cualquier página, entrada o Custom Post Type desde el panel de WordPress para renderizar el formulario.
- **Panel Dashboard (Resultados)**: Toda persona que realice el test será visible para el profesor en el menú de WordPress **"Test de Nivel"**, junto a un registro de su correo, puntaje, nivel y la fecha exacta en la que procesó su respuesta.
- **Enlaces de CTA Dinámicos (Configuración)**: Permite ingresar y auto-asignar URLs nativas (cursos, productos de woo-commerce) para cada bloque del test desde el área de ajustes sin necesidad de intervenir el código HTML/PHP.

## 📁 Estructura del Plugin

El código ha sido escrito bajo una arquitectura modular y separada, basada en Programación Orientada a Objetos:

```text
level-test-form/
├── level-test-form.php           # Archivo Base, carga dependencias, hooks y fallbacks.
├── includes/
│   ├── class-ltf-activator.php   # Ejecutado en activación (Crea la tabla wp_ltf_submissions en BD)
│   ├── class-ltf-quiz-engine.php # Lógica y banco de preguntas, calcula nivel y retorna respuesta.
│   └── class-ltf-shortcode.php   # Renderiza HTML del Shortcode y Endpoints de envío de AJAX.
├── admin/
│   └── class-ltf-admin.php       # Renderiza el "dashboard" y área de "Configuración de enlaces".
├── public/
│   ├── css/                      
│   │   └── level-test-form.css   # Variables CSS premium, media queries, fuentes de Google Fonts.
│   ├── js/                       
│   │   └── level-test-form.js    # Interactividad DOM (Siguiente/Anterior), barra de progreso y AJAX Submit.
│   └── partials/
│       └── quiz-display.php      # Estructura visual HTML de los bloques de la encuesta.
└── README.md
```

## 🛠 Instalación y Activación

1. Sube y extrae la carpeta `level-test-form/` en el directorio de tu servidor `/wp-content/plugins/` (o directamente instálalo en formato `.zip` si así lo requieres desde el panel de Administrador).
2. Ve al menú **Plugins** dentro de WordPress, localiza "Level Test Form" y haz clic en **Activar**.
3. (Opcional) Un mecanismo de protección automático creará la tabla requerida en tu base de datos y quedará lista para procesar informaciones.

## ⚙️ Guía Rápida de Uso

1. **Colocar el Formulario:**  
   Ve a Páginas/Pages en WP, edita tu página de destino y agrega un bloque **"Shortcode"** ingresando el texto literal `[test_de_nivel]`.
2. **Configurar Enlaces Comerciales:**  
   Ve a `Test de Nivel > Configuración` desde el lado izquierdo de tu panel de Administrador, e ingresa las URL de la tienda para dirigir a los estudiantes (ej. _https://todoenespanol.org/producto/curso-nivel-principiante-a1-a2/_). Esto generará de manera mágica el botón en la pantalla final de sus tests.
3. **Monitoreo de leads:**  
   Visita `Test de Nivel > Resultados` frecuentemente para observar el flujo de estudiantes e intercepciones según el correo que ingresaron para reclamar su nivel.
