# desoft-entumovil

## Instalación

    composer require desoft/entumovil

## Uso

#### Publicar assets

Correr en una terminal y marcar la opción perteciente al paquete

    php artisan vendor:publish

Después de esto se publica un archivo de configuración en la ruta config/ 

Inyectar en un controller una instancia de ConexionEnTuMovil

    public function example_controller(ConexionEnTuMovil $conexionEnTuMovil)
    {
        // Código
    }