<?php
require 'bootstrap.php';

$crypt = new \Vokuro\Services\Encryption();


$yesh = 'yesh';

$encrypted = $crypt->encrypt($yesh);

print $encrypted.PHP_EOL;

$decrypted = $crypt->decrypt($encrypted);

print $decrypted.PHP_EOL;


if($yesh !== $decrypted) throw new \Exception("Encryption Failed");
