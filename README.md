# desoft-entumovil

## Instalación

    composer require desoft/entumovil --with-all-dependencies

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

Existen 3 métodos en el objeto ConexionEnTuMovil

##### receive

    $conexionEnTuMovil->receive(Array $payload): String // devuelve el texto (sin la clave si **hasKeyword** está en true)

    $payload = [
        'hash' => $hash,
        'smsc_id' => $smscId,
        'id' => $id,
        'msisdn' => $msisdn,
        'mstext' => $mstext,
    ];

Este método se encarga de validar que el hash enviado por la api coincide con el hash que se crea a partir de los campos del payload. Depende del valor **hasKeyword** en el archivo de configuración **enTuMovil**.
Si **hasKeyword** es true espera que la clave esté presente en el mensaje y devuelve solo el texto útil del mensaje. Si es false devuelve todo el texto

##### send

    $conexionEnTuMovil->send(String $message, String $recipient, String $urlCallback = null): Json // Retorna respuesta de la api

Envía un post a la ruta definida en el archivo de configuración con el **message** que se quiere enviar, el **recipient** que es el destino (puede ser el id de un mensaje o el número de telefono) y si espera o no una respuesta de confirmación a través del campo **urlCallback**

##### callback

    $conexionEnTuMovil->callback(Array $payload): String // devuelve el estado

    $payload = [
        'cliMsgId' => $cliMsgId,
        'smscId' => $smscId,
        'hash' => $hash,
        'status' => $status,
    ];