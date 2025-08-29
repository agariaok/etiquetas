<?php
error_reporting(E_ALL); ini_set('display_errors',1);
try {
  $pdo = new PDO('mysql:host=localhost;port=3307;dbname=clients_app;charset=utf8','root','', [
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
    PDO::ATTR_TIMEOUT=>3
  ]);
  echo "OK conectado";
} catch (Throwable $e) { echo $e->getMessage(); }