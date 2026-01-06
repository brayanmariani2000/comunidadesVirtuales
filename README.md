

---

## Autor
### **Brayan Mariani**
> Desarrollador apasionado por crear soluciones tecnológicas innovadoras y funcionales.

---

##  Instrucciones de Instalación

Sigue estos pasos para poner en marcha el sistema en tu entorno local:

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/brayanmariani2000/comunidadesVirtuales.git
   cd comunidadesVirtuales
   ```

2. **Instalar dependencias de PHP:**
   ```bash
   composer install
   ```


4. **Configurar el entorno:**
   Copia el archivo de ejemplo y configura tus credenciales de base de datos en el archivo `.env`.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Preparar la base de datos:**
   Ejecuta las migraciones y los seeders para cargar la estructura y los datos iniciales.
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. opcional **Iniciar el servidor:**
   ```bash
   php artisan serve
   ```

---

## Características Principales
- **Chat en Tiempo Real:** Comunicación instantánea sin recargar la página.
- **Gestión Académica:** Control de unidades curriculares, materiales y tareas.
- **Roles de Usuario:** Interfaces personalizadas para Administradores, Profesores y Estudiantes.
- **Diseño Moderno:** Interfaz intuitiva y atractiva.

---

