<?php

namespace App\Helpers;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use \Exception;

class AuthHelper
{
    //Utilisé si pas de variable d'env
    private static $key = 'random_mill_key_fallback';

    public static function getKey()
    {
        return env('JWT_SECRET');
    }

    /**
     * @param $user array
     * @return string
     */
    public static function generateAccessToken(Array $user): string
    {

        $now = new DateTimeImmutable();

        //expire 5mins
        $expire = $now->modify('+1 year');

        //https://auth0.com/docs/secure/tokens/json-web-tokens/json-web-token-claims#registered-claims
        //Cles possible pour la vérif
        $payload = [
            'exp' => $expire->getTimestamp(), //(expiration time): Time after which the JWT expires
            'iat' => $now->getTimestamp(), //(issued at time): Time at which the JWT was issued; can be used to determine age of the JWT
            'user' => $user,
        ];

        return JWT::encode($payload, self::getKey(), 'HS256');
    }

    public static function generateRefreshToken($user): string
    {
        //expire 1 an
        $now = new DateTimeImmutable();
        $expire = $now->modify('+1 year');

        $payload = [
            'exp' => $expire->getTimestamp(), //(expiration time): Time after which the JWT expires
            'iat' => $now->getTimestamp(), //(issued at time): Time at which the JWT was issued; can be used to determine age of the JWT
            'user' => $user,
        ];

        return JWT::encode($payload, self::getKey(), 'HS256');
    }

    public static function getUserFromToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(AuthHelper::getKey(), 'HS256'));
	        return $decoded->user;
        } catch (Exception $e) {
            return $e;
        }
    }
}
