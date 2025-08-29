<?php
namespace App\Models;

class Client
{
    public $id;
    public $nombre;
    public $apellido;
    public $dni;
    public $domicilio;
    public $provincia;
    public $localidad;
    public $cp;
    public $telefono;
    public $email;
    public $transporte;
    public $peso;       // puede ser string/decimal nullable
    public $bulto;
    public $created_at; // DATETIME
}
