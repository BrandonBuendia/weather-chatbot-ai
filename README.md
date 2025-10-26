# 🌤️ Weather Chatbot AI

Chatbot interactivo con inteligencia artificial que responde consultas sobre el clima en tiempo real, utilizando la API de Open-Meteo para datos meteorológicos precisos y GPT-4o-mini de OpenAI para conversaciones naturales.

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Tecnologías](#-tecnologías)
- [Requisitos Previos](#-requisitos-previos)
- [Instalación](#-instalación)
- [Configuración](#-configuración)
- [Uso](#-uso)
- [Ejecución de Tests](#-ejecución-de-tests)
- [Arquitectura del Proyecto](#-arquitectura-del-proyecto)
- [Estructura de Directorios](#-estructura-de-directorios)
- [Prompt Engineering](#-prompt-engineering)
- [API Integrations](#-api-integrations)

---

## ✨ Características

- 💬 **Chat en tiempo real** con interfaz moderna estilo WhatsApp
- 🤖 **Inteligencia Artificial** powered by OpenAI GPT-4o-mini
- 🌍 **Datos meteorológicos reales** de Open-Meteo API
- 📊 **28 condiciones climáticas** con descripciones en español y emojis
- 💾 **Historial persistente** de conversaciones
- 🔒 **Protección contra prompt injection**
- 🎨 **UI/UX responsive** con Tailwind CSS
- ⚡ **Auto-scroll** y feedback visual (loading, errores)
- 🌐 **Geocoding automático** de ciudades a coordenadas
- 📱 **Mobile-friendly** y diseño adaptativo

---

## 🚀 Tecnologías

### Backend
- **Laravel 12** - Framework PHP
- **PHP 8.2+** - Lenguaje de programación
- **MySQL 8.0+** - Base de datos relacional
- **OpenAI PHP Client** - Integración con GPT-4o-mini
- **Guzzle HTTP** - Cliente HTTP para APIs externas

### Frontend
- **Vue 3** - Framework JavaScript progresivo
- **Inertia.js** - SPA adapter para Laravel
- **Tailwind CSS** - Framework CSS utility-first
- **Vite** - Build tool y dev server
- **Axios** - Cliente HTTP para llamadas AJAX

### Patrones y Arquitectura
- **Actions Pattern** - Lógica de negocio desacoplada
- **Repository Pattern** - Eloquent ORM
- **DTO Pattern** - Data Transfer Objects inmutables
- **Service Pattern** - Integraciones externas
- **SOLID Principles** - Código mantenible y escalable

---

## 📦 Requisitos Previos

Antes de instalar, asegúrate de tener:

- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.x
- **NPM** >= 9.x
- **MySQL** >= 8.0 (o MariaDB >= 10.3)
- **Cuenta OpenAI** con API Key (https://platform.openai.com/api-keys)

### Verificar versiones instaladas

```bash
php -v        # PHP 8.2 o superior
composer -V   # Composer 2.x
node -v       # Node.js 18.x o superior
npm -v        # NPM 9.x o superior
mysql -V      # MySQL 8.0 o superior
```

---

## 🛠️ Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/weather-chatbot-ai.git
cd weather-chatbot-ai
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Configurar archivo de entorno

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar la base de datos

Edita el archivo `.env` con tus credenciales de MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=weather_chatbot_ai
DB_USERNAME=root
DB_PASSWORD=tu_password_mysql
```

### 5. Crear la base de datos

```bash
# Opción 1: Crear manualmente desde MySQL
mysql -u root -p
CREATE DATABASE weather_chatbot_ai CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Opción 2: Desde terminal (Linux/Mac)
mysql -u root -p -e "CREATE DATABASE weather_chatbot_ai CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
```

### 6. Ejecutar migraciones

```bash
php artisan migrate
```

### 7. Configurar OpenRouter API Key (Recomendado)

Por defecto, el proyecto usa **OpenRouter** para evitar límites de tasa restrictivos.

Obtén tu API Key en: https://openrouter.ai/keys

Edita el archivo `.env` y agrega:

```env
OPENAI_API_KEY=sk-or-v1-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
OPENAI_API_BASE=https://openrouter.ai/api/v1
```

**Alternativa - Usar OpenAI directamente:**

Si prefieres usar OpenAI directamente (https://platform.openai.com/api-keys):

```env
OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
OPENAI_API_BASE=https://api.openai.com/v1
```

⚠️ **IMPORTANTE**: Nunca compartas ni subas tu API Key a repositorios públicos.

### 8. Instalar dependencias de Node.js

```bash
npm install
```

### 9. Compilar assets del frontend

```bash
# Para desarrollo (con hot reload)
npm run dev

# Para producción (optimizado)
npm run build
```

### 10. Iniciar el servidor de desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en: **http://localhost:8000**

---

## ⚙️ Configuración

### Variables de Entorno Importantes

```env
# Aplicación
APP_NAME="Weather Chatbot AI"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de Datos
DB_CONNECTION=mysql
DB_DATABASE=weather_chatbot_ai
DB_USERNAME=root
DB_PASSWORD=

# OpenRouter (Recommended)
OPENAI_API_KEY=sk-or-v1-xxxxx
OPENAI_API_BASE=https://openrouter.ai/api/v1

# Sesión y Cache
SESSION_DRIVER=database
CACHE_STORE=database
```

### Configuración Opcional

Si deseas cambiar el modelo de OpenAI, edita:

`app/Services/OpenAIService.php` línea 27:

```php
'model' => 'gpt-4o-mini', // Cambiar a 'gpt-4' para mejor calidad
```

---

## 📖 Uso

### Flujo Básico

1. **Accede a la aplicación**: http://localhost:8000
2. **Inicia una nueva conversación**: Click en "Iniciar Nueva Conversación"
3. **Pregunta sobre el clima**: Escribe consultas como:
   - "¿Qué temperatura hace en Madrid?"
   - "¿Lloverá en Barcelona mañana?"
   - "¿Cómo está el clima en París?"
   - "¿Necesito paraguas en Londres?"

4. **Recibe respuesta con datos reales**: MeteoBot consultará la API de Open-Meteo y responderá con:
   - Temperatura actual
   - Condiciones meteorológicas
   - Recomendaciones prácticas
   - Emojis visuales

### Ejemplos de Consultas

```
Usuario: "¿Qué tiempo hace en Berlín?"

MeteoBot: "¡Déjame consultar el clima actual de Berlín para ti 🌍

**🌧️ Berlín**
🌡️ Temperatura: 14.2°C
🌧️ Condiciones: Lluvia leve
💡 Recomendación: ¡Lleva paraguas! Se espera lluvia durante el día."
```

---

## 🧪 Ejecución de Tests

El proyecto incluye tests unitarios y de integración.

### Ejecutar todos los tests

```bash
php artisan test
```

### Ejecutar tests con coverage

```bash
php artisan test --coverage
```

### Ejecutar tests específicos

```bash
# Solo tests unitarios
php artisan test --testsuite=Unit

# Solo tests feature
php artisan test --testsuite=Feature

# Test específico
php artisan test --filter=ChatTest
```

### Tests Incluidos

- ✅ **Unit Tests**: OpenAIService, OpenMeteoService, Actions, DTOs, Enums
- ✅ **Feature Tests**: ChatController, conversaciones, mensajes
- ✅ **Auth Tests**: Login, registro, password reset

---

## 🏗️ Arquitectura del Proyecto

### Patrón de Capas

```
┌─────────────────────────────────────┐
│         Frontend (Vue 3)            │
│  - Components, Pages, Layouts       │
└──────────────┬──────────────────────┘
               │ Inertia.js
┌──────────────▼──────────────────────┐
│      Controllers (Laravel)          │
│  - ChatController, AuthControllers  │
└──────────────┬──────────────────────┘
               │
┌──────────────▼──────────────────────┐
│         Actions Layer               │
│  - CreateConversationAction         │
│  - SendMessageAction                │
└──────────────┬──────────────────────┘
               │
┌──────────────▼──────────────────────┐
│        Services Layer               │
│  - OpenAIService (GPT)              │
│  - OpenMeteoService (Weather)       │
└──────────────┬──────────────────────┘
               │
┌──────────────▼──────────────────────┐
│         Models Layer                │
│  - Conversation, Message            │
│  - Eloquent ORM                     │
└──────────────┬──────────────────────┘
               │
┌──────────────▼──────────────────────┐
│          Database                   │
│  - MySQL (conversations, messages)  │
└─────────────────────────────────────┘
```

### Componentes Principales

#### **Actions** (`app/Actions/`)
- `CreateConversationAction`: Crea nuevas conversaciones
- `SendMessageAction`: Envía mensajes y obtiene respuestas del asistente

#### **Services** (`app/Services/`)
- `OpenAIService`: Integración con OpenAI GPT-4o-mini
- `OpenMeteoService`: Integración con Open-Meteo Weather API

#### **DTOs** (`app/DTOs/`)
- `WeatherData`: Objeto inmutable para datos meteorológicos

#### **Enums** (`app/Enums/`)
- `MessageRole`: USER | ASSISTANT
- `WeatherCode`: 28 códigos WMO con descripciones y emojis

#### **Models** (`app/Models/`)
- `Conversation`: Conversaciones del chat
- `Message`: Mensajes individuales

---

## 📁 Estructura de Directorios

```
weather-chatbot-ai/
├── app/
│   ├── Actions/                 # Lógica de negocio
│   │   ├── CreateConversationAction.php
│   │   └── SendMessageAction.php
│   ├── DTOs/                    # Data Transfer Objects
│   │   └── WeatherData.php
│   ├── Enums/                   # Enumeraciones
│   │   ├── MessageRole.php
│   │   └── WeatherCode.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ChatController.php
│   │   │   └── Auth/           # Controladores de autenticación
│   │   └── Middleware/
│   ├── Models/                  # Eloquent Models
│   │   ├── Conversation.php
│   │   ├── Message.php
│   │   └── User.php
│   └── Services/                # Servicios externos
│       ├── OpenAIService.php
│       └── OpenMeteoService.php
├── database/
│   └── migrations/              # Migraciones de BD
│       ├── xxxx_create_conversations_table.php
│       └── xxxx_create_messages_table.php
├── resources/
│   └── js/
│       ├── Components/          # Componentes Vue reutilizables
│       ├── Layouts/             # Layouts (Authenticated, Guest)
│       └── Pages/
│           ├── Chat/
│           │   ├── Index.vue   # Lista de conversaciones
│           │   └── Show.vue    # Interfaz de chat
│           └── Auth/            # Páginas de autenticación
├── routes/
│   ├── web.php                  # Rutas web
│   └── auth.php                 # Rutas de autenticación
├── tests/
│   ├── Feature/                 # Tests de integración
│   └── Unit/                    # Tests unitarios
├── .env.example                 # Plantilla de variables de entorno
├── composer.json                # Dependencias PHP
├── package.json                 # Dependencias Node.js
├── tailwind.config.js           # Configuración Tailwind
└── vite.config.js               # Configuración Vite
```

---

## 🧠 Prompt Engineering

El proyecto utiliza un **prompt altamente optimizado** para el asistente de IA:

### Características del Prompt

- ✅ **Rol definido**: "MeteoBot", asistente meteorológico
- ✅ **Objetivos claros**: Proporcionar información útil sobre clima
- ✅ **Reglas y limitaciones**: Solo responde temas meteorológicos
- ✅ **Formato de respuesta**: Especificado con emojis y markdown
- ✅ **Ejemplos de interacciones**: Casos ideales documentados
- ✅ **Manejo de ambigüedad**: Estrategias para consultas vagas
- ✅ **Protección contra prompt injection**: Validaciones de seguridad
- ✅ **Personalidad**: Amigable, profesional, conciso

### Ubicación

El prompt completo está en: `app/Services/OpenAIService.php` método `buildSystemPrompt()`

---

## 🌐 API Integrations

### OpenRouter / OpenAI API

Por defecto, el proyecto usa **OpenRouter** como proxy para acceder a modelos de OpenAI con mejores límites de tasa.

- **Proveedor**: OpenRouter (https://openrouter.ai)
- **Modelo**: gpt-4o-mini
- **Temperatura**: 0.7 (balance entre creatividad y precisión)
- **Max Tokens**: 500 (respuestas concisas)
- **Ventajas de OpenRouter**:
  - Mejores límites de tasa que OpenAI directo
  - Soporte para múltiples proveedores de LLM
  - Precios competitivos
  - Manejo mejorado de errores
- **Documentación**:
  - OpenRouter: https://openrouter.ai/docs
  - OpenAI: https://platform.openai.com/docs

### Open-Meteo API

- **Endpoint Forecast**: https://api.open-meteo.com/v1/forecast
- **Endpoint Geocoding**: https://geocoding-api.open-meteo.com/v1/search
- **Características**: Gratuita, sin API key, datos actualizados
- **Documentación**: https://open-meteo.com/en/docs

---

## 🤝 Contribución

Este es un proyecto de prueba técnica. Para proyectos similares:

1. Fork el repositorio
2. Crea una rama feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -m 'feat: agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

### Convención de Commits

```
feat: nueva característica
fix: corrección de bug
docs: cambios en documentación
style: formato, punto y coma faltantes, etc
refactor: refactorización de código
test: agregar tests faltantes
chore: actualizar tareas de build, configuraciones, etc
```

---

## 📄 Licencia

Este proyecto fue desarrollado como prueba técnica para el cargo de **Desarrollador Fullstack**.

---

## 👨‍💻 Autor

Desarrollado con ❤️ utilizando Laravel, Vue.js e Inteligencia Artificial.

---

## 🐛 Troubleshooting

### Error: "SQLSTATE[HY000] [2002] Connection refused"

**Solución**: Verifica que MySQL esté corriendo:

```bash
# Linux/Mac
sudo service mysql start

# Windows
net start MySQL80
```

### Error: "Class 'OpenAI' not found"

**Solución**: Limpia cache y reinstala:

```bash
composer dump-autoload
php artisan config:clear
composer require openai-php/laravel
```

### Error: "Vite manifest not found"

**Solución**: Compila los assets:

```bash
npm run build
```

### Error: "Se ha excedido el límite de peticiones"

**Solución**:

1. **Recomendado**: Cambia a OpenRouter en tu `.env`:
   ```env
   OPENAI_API_KEY=sk-or-v1-tu_api_key
   OPENAI_API_BASE=https://openrouter.ai/api/v1
   ```

2. Si usas OpenAI directo, verifica tus límites: https://platform.openai.com/account/limits

3. Espera 1-2 minutos antes de reintentar (los límites se resetean automáticamente)

---

## 📞 Soporte

Para preguntas o problemas:

- **Issues**: Abre un issue en GitHub
- **Email**: tu-email@example.com

---

**¡Gracias por revisar este proyecto!** 🚀

# cGFuZ29saW4=
