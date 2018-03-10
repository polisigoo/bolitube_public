<?php

namespace App\Library;

use App\Token;
use DateTime;

class GernerateUniqueID{

    function generate_random_id_yt($entropy = 0){
        $text = base_convert(microtime(false), 10, 36);

        if ($entropy === 0) {
            $text;
        }elseif($entropy === 1){
            $text .= mt_rand(0, 65);
        }elseif($entropy === 2){
            $text = ucfirst((str_shuffle($text)));
        }elseif($entropy === 3){
            $text = strrev(ucfirst(strrev($text)));
        }

        return $text;
    }

    function generate_GUID(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }
        else {
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = /*chr(123)// "{"
                .*/substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)/*
                .chr(125)*/;// "}"
            return $uuid;
        }
    }

    /**
     * @param string $ip
     * @return string $newtoken
     */
    function create_first_token($ip = null){
        $newtoken = $this->generate_GUID();

        $token = new Token();
        $token->token = $newtoken;
        $token->ip = e($ip);
        $token->save();

        return $newtoken;
    }

    /**
     * @param string $token
     * @param string $ip
     * @return string $newtoken
     */
    function verify_and_return($token, $ip = null){
        $old = Token::where('token', e($token))->first();

        if (!empty($old)){
            $newtoken = $this->create_first_token(e($ip));

            return $newtoken;
        }else{
            return 'Invalid token. Expired or non-existent';
        }
    }

    /**
     * @param string $ip
     * @return Token $alive
     */
    function verifyOldsTokens($ip){
        $olds = Token::where('ip', e($ip))->get();
        $alive = null;

        foreach ($olds as $old){
            $date1 = new DateTime($old->created_at);
            $date2 = new DateTime(date("Y-m-d H:i:s"));
            $interval = $date1->diff($date2);
            $diferencia = $interval->h;

            if ($diferencia > 2){
                $old->delete();
            }else{
                $alive = $old->token;
            }
        }

        return $alive;
    }
}