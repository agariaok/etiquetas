<?php
namespace App\Models;

class Client
{
    public $id = null;
    public $nombre = '';
    public $apellido = '';
    public $dni = '';
    public $domicilio = '';
    public $provincia = '';
    public $localidad = '';
    public $cp = '';
    public $telefono = '';
    public $email = '';
    public $transporte = '';
    public $peso = '';
    public $bulto = '';
    public $created_at = '';

    public function __construct(array $data = [])
    {
        $this->id         = isset($data['id']) ? (int)$data['id'] : null;
        $this->nombre     = trim(isset($data['nombre']) ? $data['nombre'] : '');
        $this->apellido   = trim(isset($data['apellido']) ? $data['apellido'] : '');
        $this->dni        = trim(isset($data['dni']) ? $data['dni'] : '');
        $this->domicilio  = trim(isset($data['domicilio']) ? $data['domicilio'] : '');
        $this->provincia  = trim(isset($data['provincia']) ? $data['provincia'] : '');
        $this->localidad  = trim(isset($data['localidad']) ? $data['localidad'] : '');
        $this->cp         = trim(isset($data['cp']) ? $data['cp'] : '');
        $this->telefono   = trim(isset($data['telefono']) ? $data['telefono'] : '');
        $this->email      = trim(isset($data['email']) ? $data['email'] : '');
        $this->transporte = trim(isset($data['transporte']) ? $data['transporte'] : '');
        $this->peso       = trim(isset($data['peso']) ? $data['peso'] : '');
        $this->bulto      = trim(isset($data['bulto']) ? $data['bulto'] : '');
        $this->created_at = isset($data['created_at']) ? $data['created_at'] : date('Y-m-d H:i:s');
    }

    public function validate()
    {
        $errors = [];
        if ($this->nombre === '') $errors['nombre'] = 'Nombre requerido';
        if ($this->apellido === '') $errors['apellido'] = 'Apellido requerido';
        if ($this->dni === '' || !preg_match('/^\\d{7,9}$/', $this->dni)) $errors['dni'] = 'DNI inválido';
        if ($this->domicilio === '') $errors['domicilio'] = 'Domicilio requerido';
        if ($this->provincia === '') $errors['provincia'] = 'Provincia requerida';
        if ($this->localidad === '') $errors['localidad'] = 'Localidad requerida';
        if ($this->cp === '' || !preg_match('/^\\d{4}$/', $this->cp)) $errors['cp'] = 'CP inválido (4 dígitos)';
        if ($this->telefono === '' || !preg_match('/^\\d{6,15}$/', $this->telefono)) $errors['telefono'] = 'Teléfono inválido';
        if ($this->email === '' || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Email inválido';
        if ($this->transporte === '') $errors['transporte'] = 'Indicar transporte';
        if ($this->peso !== '' && !preg_match('/^[0-9]+(\\.[0-9]{1,2})?$/', $this->peso)) $errors['peso'] = 'Peso inválido';
        if ($this->bulto === '' || !preg_match('/^\\d+$/', $this->bulto)) $errors['bulto'] = 'Bulto inválido';
        return $errors;
    }
}
