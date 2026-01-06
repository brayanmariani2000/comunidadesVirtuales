```markdown
╔══════════════════════════════════════════════════════════════╗
║                                                              ║
║  ██████╗ ██████╗   █████╗ ██╗   ██╗ █████╗ ███╗   ██╗       ║
║  ██╔══██╗██╔══██╗ ██╔══██╗╚██╗ ██╔╝██╔══██╗████╗  ██║       ║
║  ██████╔╝██████╔╝ ███████║ ╚████╔╝ ███████║██╔██╗ ██║       ║
║  ██╔══██╗██╔══██╗ ██╔══██║  ╚██╔╝  ██╔══██║██║╚██╗██║       ║
║  ██████╔╝██║  ██║ ██║  ██║   ██║   ██║  ██║██║ ╚████║       ║
║  ╚═════╝ ╚═╝  ╚═╝ ╚═╝  ╚═╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═══╝       ║
║                                                              ║
║  ███╗   ███╗ █████╗ ██████╗ ██╗ █████╗ ███╗   ██╗██╗       ║
║  ████╗ ████║██╔══██╗██╔══██╗██║██╔══██╗████╗  ██║██║       ║
║  ██╔████╔██║███████║██████╔╝██║███████║██╔██╗ ██║██║       ║
║  ██║╚██╔╝██║██╔══██║██╔══██╗██║██╔══██║██║╚██╗██║██║       ║
║  ██║ ╚═╝ ██║██║  ██║██║  ██║██║██║  ██║██║ ╚████║██║       ║
║  ╚═╝     ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚═╝  ╚═══╝╚═╝       ║
║                                                              ║
╠══════════════════════════════════════════════════════════════╣
║                                                              ║
║               SISTEMA DE COMUNIDADES VIRTUALES               ║
║                       EDUCATIVAS                            ║
║                                                              ║
╠══════════════════════════════════════════════════════════════╣
║                                                              ║
║                    AUTOR:                            ║
║                                                              ║
║               <span style="color: #ff0000; font-weight: bold;">
               B R A Y A N   M A R I A N I
               </span>                                        ║
║                                                              ║
║           "Desarrollador Full Stack Apasionado"              ║
║                                                              ║
╚══════════════════════════════════════════════════════════════╝

##  INSTALACIÓN RÁPIDA

```bash
# 1. Clonar repositorio
git clone https://github.com/brayanmariani2000/comunidadesVirtuales.git

# 2. Navegar al directorio
cd comunidadesVirtuales

# 3. Instalar dependencias PHP
composer install

# 4. Configurar entorno
cp .env.example .env
php artisan key:generate

# 5. Configurar base de datos (en .env)
# DB_DATABASE=comunidades_virtuales
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Ejecutar migraciones
php artisan migrate

# 7. Poblar base de datos
php artisan db:seed

# 8. Instalar dependencias frontend


# 9. Iniciar servidor
php artisan serve