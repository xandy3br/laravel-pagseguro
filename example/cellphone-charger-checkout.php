<?php

require '_prevent-access.php';
require_once __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 'On');
error_reporting(E_ALL);

/**
 * Change Credentials to your SandBox
 */
$sandBoxCredentials = include '_sandbox-credentials.php';
$credentialEmail = $sandBoxCredentials['email']; // Email
$credentialKey = $sandBoxCredentials['key']; // Public Key
$senderEmail = 'ligador@sandbox.pagseguro.com.br'; // Sender-Email

use laravel\pagseguro\Config\Config;
use laravel\pagseguro\Credentials\Credentials;
use laravel\pagseguro\Checkout\Facade\CheckoutFacade;

$data = [
    'items' => [
        [
            'id' => 20,
            'description' => 'Recarga de Celular',
            'quantity' => 1,
            'amount' => 21
        ]
    ],
    'sender' => [
        'email' => $senderEmail,
        'name' => 'Isaque de Souza Barbosa',
        'documents' => [
            [
                'number' => '40404040411',
                'type' => 'CPF'
            ]
        ],
        'phone' => '11985445522',
        'bornDate' => '1988-03-21',
    ],
    'currency' => 'BRL',
    'cellphone_charger' => '+5511980840022'
];

try {
    Config::set('use-sandbox', true);
    $facade = new CheckoutFacade();
    $credentials = new Credentials($credentialKey, $credentialEmail);
    $checkout = $facade->createFromArray($data);
    $information = $checkout->send($credentials);
    printf('<pre>%s</pre>', print_r($information, 1));
    printf('<a href="%s">Clique para pagar</a>', $information->getLink());
} catch (\Exception $e) {
    printf('<pre>%s</pre>', print_r((string)$e, 1));
}
