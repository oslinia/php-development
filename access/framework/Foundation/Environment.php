<?php

namespace Framework\Foundation;

use Framework\Foundation\Feature\Buffer;

function buffer(): string
{
    ob_start();

    extract(Buffer::$context);

    require Buffer::$file;

    return ob_get_clean();
}

function salt_decrypt(string $string): string
{
    $decode = base64_decode($string);
    $ivlen = openssl_cipher_iv_length('AES-128-CBC');

    return openssl_decrypt(
        substr($decode, $ivlen + 32),
        'AES-128-CBC',
        $GLOBALS['_FW']->env['salt'],
        OPENSSL_RAW_DATA,
        substr($decode, 0, $ivlen),
    );
}

function salt_encrypt(string $string): string
{
    $raw = openssl_encrypt(
        $string,
        'AES-128-CBC',
        $GLOBALS['_FW']->env['salt'],
        OPENSSL_RAW_DATA,
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CBC')),
    );

    return base64_encode($iv . hash_hmac(
        'sha256',
        $raw,
        $GLOBALS['_FW']->env['salt'],
        true
    ) . $raw);
}

class Environment extends Env
{
    private array $map;

    public function __construct(string $path_info, string $map)
    {
        parent::__construct($path_info);

        $this->map = require $map;

        $GLOBALS['_FW'] = $this;
    }

    public function collect(string $name, array $args): null|string
    {
        if (isset($this->map[$name])) {
            $route = $this->map[$name];

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
