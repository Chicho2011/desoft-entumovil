<?php

namespace Desoft\Tests;

use Desoft\Classes\CustomException;
use Desoft\Services\EnTuMovilServices;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;


class EnTuMovilTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_my_first_test(){
        $this->assertTrue(true);
    }

    public function test_receive()
    {
        Config::set('enTuMovil.user', 'user');
        $user = config('enTuMovil.user');

        Config::set('enTuMovil.pass', 'pass');
        $pass = strtoupper(config('enTuMovil.pass'));

        Config::set('enTuMovil.keyword', 'prueba');
        Config::set('enTuMovil.hasKeyword', true);

        Config::set('enTuMovil.smscId', 'cubacel');
        $smscId = config('enTuMovil.smscId');

        $id = 1;
        $msisdn = 1;
        $mstext = 'prueba 123';

        $toHash = implode(':', [$user, $pass, $smscId, $id, $msisdn, $mstext]);
        $hashed = sha1($toHash);

        $enTuMovilConexion = $this->app->make('Desoft\Classes\ConexionEnTuMovil');

        $response = $enTuMovilConexion->receive([
            'hash' => $hashed,
            'smsc_id' => $smscId,
            'id' => $id,
            'msisdn' => $msisdn,
            'mstext' => $mstext
        ]);

        $this->assertEquals('123', $response);
    }

    public function test_keyword_not_present_receive(){
        Config::set('enTuMovil.user', 'user');
        $user = config('enTuMovil.user');

        Config::set('enTuMovil.pass', 'pass');
        $pass = strtoupper(config('enTuMovil.pass'));

        Config::set('enTuMovil.keyword', 'prueba');
        Config::set('enTuMovil.hasKeyword', false);

        Config::set('enTuMovil.smscId', 'cubacel');
        $smscId = config('enTuMovil.smscId');

        $id = 1;
        $msisdn = 1;
        $mstext = 'prueba 123';

        $toHash = implode(':', [$user, $pass, $smscId, $id, $msisdn, $mstext]);
        $hashed = sha1($toHash);

        $enTuMovilConexion = $this->app->make('Desoft\Classes\ConexionEnTuMovil');

        $response = $enTuMovilConexion->receive([
            'hash' => $hashed,
            'smsc_id' => $smscId,
            'id' => $id,
            'msisdn' => $msisdn,
            'mstext' => $mstext
        ]);

        $this->assertEquals('prueba 123', $response);
    }

    public function test_invalid_text_receive(){
        Config::set('enTuMovil.user', 'user');
        $user = config('enTuMovil.user');

        Config::set('enTuMovil.pass', 'pass');
        $pass = strtoupper(config('enTuMovil.pass'));

        Config::set('enTuMovil.keyword', 'prueba');
        Config::set('enTuMovil.hasKeyword', true);

        Config::set('enTuMovil.smscId', 'cubacel');
        $smscId = config('enTuMovil.smscId');

        $id = 1;
        $msisdn = 1;
        $mstext = 'prueba';

        $toHash = implode(':', [$user, $pass, $smscId, $id, $msisdn, $mstext]);
        $hashed = sha1($toHash);

        $enTuMovilConexion = $this->app->make('Desoft\Classes\ConexionEnTuMovil');

        $this->expectException(CustomException::class);
        $this->expectExceptionMessage('Texto no válido');
        $response = $enTuMovilConexion->receive([
            'hash' => $hashed,
            'smsc_id' => $smscId,
            'id' => $id,
            'msisdn' => $msisdn,
            'mstext' => $mstext
        ]);

    }

    public function test_send_payload(){
        Config::set('enTuMovil.user', 'user');
        $user = 'user';

        Config::set('enTuMovil.pass', 'pass');
        $pass = strtoupper('pass');

        Config::set('enTuMovil.keyword', 'prueba');
        Config::set('enTuMovil.hasKeyword', true);
        Config::set('enTuMovil.smscId', 'cubacel');

        $smscId = 'cubacel';
        $enTuMovilServices = new EnTuMovilServices(
                                                    config('enTuMovil.keyword'),
                                                    config('enTuMovil.hasKeyword'),
                                                    config('enTuMovil.user'),
                                                    config('enTuMovil.pass'),
                                                    config('enTuMovil.smscId')
                                                );

        $message = 'Prueba de mensaje';
        $recipient = '5352522757';

        $response = $enTuMovilServices->prepareDataToSend($message,$recipient);
        $responseHash = $response['hash'];

        $ownHash = sha1(implode(':', [
                                        $response['userId'],
                                        $pass,
                                        $response['smscId'],
                                        $response['id'],
                                        $response['recipient'],
                                        urlencode($response['mstext']),
        ]));

        $this->assertEquals($responseHash, $ownHash);
    }

    public function test_callback(){

        Config::set('enTuMovil.user', 'user');
        Config::set('enTuMovil.pass', 'pass');
        Config::set('enTuMovil.keyword', 'prueba');
        Config::set('enTuMovil.hasKeyword', true);
        Config::set('enTuMovil.smscId', 'cubacel');

        $cliMsgId = 1;
        $user = 'user';
        $pass = strtoupper('pass');
        $smscId = 'cubacel';
        $status = '003';

        $toHash = implode(':', [$user, $pass, $smscId, $cliMsgId, $status]);
        $hashed = sha1($toHash);

        $enTuMovilConexion = $this->app->make('Desoft\Classes\ConexionEnTuMovil');

        $response = $enTuMovilConexion->callback([
            'hash' => $hashed,
            'smsc_id' => $smscId,
            'cliMsgId' => $cliMsgId,
            'status' => $status,
        ]);

        $this->assertEquals('003', $response);
    }

}
