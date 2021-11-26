<?php

namespace Desoft\Classes;

use Exception;
use Desoft\Classes\CustomException;
use Desoft\Services\ConexionServices;
use Desoft\Services\EnTuMovilServices;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ConexionEnTuMovil {

    private $enTuMovilServices;
    private $conexionServices;

    public function __construct(EnTuMovilServices $enTuMovilServices, ConexionServices $conexionServices)
    {
        $this->enTuMovilServices = $enTuMovilServices;
        $this->conexionServices = $conexionServices;
    }

    public function receive(Array $request)
    {
        $hash = $request['hash'] ?? '';

        $smscId = $request['smsc_id'] ?? '';
        $id = $request['id'] ?? '';
        $msisdn = $request['msisdn'] ?? '';
        $mstext = $request['mstext'] ?? '';
        $sender = $request['sender'] ?? '';

        $checkHash = $this->enTuMovilServices->checkHash($hash, [
            config('enTuMovil.user'),
            strtoupper(config('enTuMovil.pass')),
            $smscId,
            $id,
            $msisdn,
            $mstext,
        ]);

        if($checkHash)
        {
            try{
                $code = $this->enTuMovilServices->processContent($mstext);
                return $code;
            }
            catch(CustomException $e)
            {
                throw new CustomException($e->getMessage(), $id);
            }
        }

        throw new CustomException("Hash no coincide", $id);
    }

    public function send($message, $recipient, $urlCallback = null)
    {
        $query = $this->enTuMovilServices->prepareDataToSend($message, $recipient, $urlCallback);

        try{
            $response = $this->conexionServices->send(config('enTuMovil.url'), $query);
            return $response;
        }
        catch(Exception $e)
        {
            throw new CustomException("Error al enviar", null);

        }
    }

    public function callback(Array $request)
    {
        $cliMsgId = $request['cliMsgId'] ?? '';
        $smscId = $request['smsc_id'] ?? '';
        $hash = $request['hash'] ?? '';
        $status = $request['status'] ?? '';

        $checkHash = $this->enTuMovilServices->checkHash($hash, [
            config('enTuMovil.user'),
            strtoupper(config('enTuMovil.pass')),
            $smscId,
            $cliMsgId,
            $status
        ]);

        if($checkHash)
        {
            return $status;
        }

        throw new CustomException("Hash no coincide", null);

    }

}
