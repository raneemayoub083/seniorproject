<?php

namespace App\Helpers;

class MessageEncryption
{
    public static function encrypt($plaintext)
    {
        $key = base64_decode(env('MESSAGE_ENCRYPTION_KEY'));
        $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $ciphertext = openssl_encrypt($plaintext, 'aes-256-cbc', $key, 0, $iv);

        return base64_encode($iv . $ciphertext);
    }

    public static function decrypt($encoded)
    {
        $key = base64_decode(env('MESSAGE_ENCRYPTION_KEY'));
        $data = base64_decode($encoded);
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');

        $iv = substr($data, 0, $ivLength);
        $ciphertext = substr($data, $ivLength);

        return openssl_decrypt($ciphertext, 'aes-256-cbc', $key, 0, $iv);
    }
}
