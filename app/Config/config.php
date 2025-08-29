
<?php
return [
    'debug' => true,
    'db' => [
        'dsn'      => 'mysql:host=127.0.0.1;port=3307;dbname=clients_app;charset=utf8',
        'user'     => 'root',
        'password' => '',
        // Alternativa SQLite:
        // 'dsn' => 'sqlite:' . __DIR__ . '/../../database/clients.sqlite',
        // 'user' => null,
        // 'password' => null,
    ],
    'sender' => [
        'nombre'      => 'Ingrid Stefany',
        'apellido'    => 'Miño Silva',
        'dni'         => '94597165',
        'localidad'   => 'CABA',
        'cp'          => '1437',
        'telefono'    => '1130850303',
        'email'       => 'agaria.ok@gmail.com',
    ],
];
