<?php

namespace PHPFrame\Foundation;

function decrypt(string $string): string
{
    $decode = base64_decode($string);

    $length = openssl_cipher_iv_length('AES-128-CBC');

    return openssl_decrypt(
        substr($decode, $length + 32),
        'AES-128-CBC',
        State::$config['salt'],
        OPENSSL_RAW_DATA,
        substr($decode, 0, $length)
    );
}

function encrypt(string $string): string
{
    $encrypt = openssl_encrypt(
        $string,
        'AES-128-CBC',
        State::$config['salt'],
        OPENSSL_RAW_DATA,
        $pseudo_bytes = openssl_random_pseudo_bytes(
            openssl_cipher_iv_length('AES-128-CBC')
        )
    );

    return base64_encode($pseudo_bytes . hash_hmac(
        'sha256',
        $encrypt,
        State::$config['salt'],
        true
    ) . $encrypt);
}

class State
{
    public static string $path_info;

    public static array $config;

    private static array $map;

    public function __construct(string $path_to_config, string $path_to_map)
    {
        self::$path_info = explode('?', $_SERVER['REQUEST_URI'])[0];

        self::$config = require $path_to_config;
        self::$map = require $path_to_map;
    }

    public static function url(string $name, array $args): null|string
    {
        if (isset(self::$map[$name])) {
            $route = self::$map[$name];

            $size = count($args);

            if (isset($route[$size])) {
                [$path, $pattern] = $route[$size];

                foreach ($args as $mask => $value) {
                    $path = str_replace('{' . $mask . '}', $value, $path);
                }

                if (preg_match($pattern, $path, $matches)) {
                    return $matches[0];
                }
            }
        }

        return null;
    }
}
