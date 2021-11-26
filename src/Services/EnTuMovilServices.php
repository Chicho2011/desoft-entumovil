<?php

namespace Desoft\Services;

use Illuminate\Support\Str;
use Desoft\Classes\CustomException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class EnTuMovilServices {

    private $keyword;
    private $hasKeyword;
    private $user;
    private $upperPass;
    private $smscId;


    public function __construct($keyword, $hasKeyword, $user, $pass, $smscId = 'cubacel')
    {
        $this->keyword = $keyword;
        $this->hasKeyword = $hasKeyword;
        $this->user = $user;
        $this->upperPass = strtoupper($pass);
        $this->smscId = $smscId;
    }

    public function checkHash($hash, $itemsArray)
    {
        $itemsImploded = implode(':',$itemsArray);
        $hashed = sha1($itemsImploded);

        return $hashed == $hash;
    }

    public function processContent($text)
    {
        if($text)
        {
            if($this->hasKeyword)
            {
                $keyword_space = $this->keyword.' ';
                if(!\Illuminate\Support\Str::startsWith($text, $keyword_space) || \Illuminate\Support\Str::length($text) == \Illuminate\Support\Str::length($keyword_space))
                {
                    throw new CustomException('Texto no válido', null);
                }

                $code = explode($keyword_space, $text)[1];

                return $code;
            }

            return $text;
        }

        throw new CustomException('Texto inexistente', null);
    }

    public function prepareDataToSend($message, $recipient, $urlCallback = null)
    {
        $uuid = Str::uuid();

        $id = $uuid->getHex()->toString();
        $messageToHashCheck = urlencode($message);
        $dlrUrlToHashCheck = $urlCallback ? urlencode($urlCallback) : null;

        $a = $urlCallback ? [
            $this->user,
            $this->upperPass,
            $this->smscId,
            $id,
            $recipient,
            $messageToHashCheck,
            $dlrUrlToHashCheck
        ] : [
            $this->user,
            $this->upperPass,
            $this->smscId,
            $id,
            $recipient,
            $messageToHashCheck,
        ];

        $hashed = sha1(implode(':', $a));
        $query = $urlCallback ? [
            'userId' => $this->user,
            'smscId' => $this->smscId,
            'id' => $id,
            'hash' => $hashed,
            'recipient' => $recipient,
            'mstext' => $message,
            'dlrUrl' => $urlCallback
        ] : [
            'userId' => $this->user,
            'smscId' => $this->smscId,
            'id' => $id,
            'hash' => $hashed,
            'recipient' => $recipient,
            'mstext' => $message,
        ];

        return $query;
    }


}
