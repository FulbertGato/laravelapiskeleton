<?php

function caurisCrypt($plainText): string
{
    $key = env('APP_KEY');
    $iv = substr($key, 0, 16);
    $cipher = "AES-256-CBC";
    return openssl_encrypt($plainText, $cipher, $key, 0, $iv);

}

function caurisDecrypt($cipherText): string
{
    $key = env('APP_KEY');
    $iv = substr($key, 0, 16);
    $cipher = "AES-256-CBC";
    return openssl_decrypt($cipherText, $cipher, $key, 0, $iv);
}
