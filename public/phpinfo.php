<?php
error_reporting(E_ALL); ini_set('display_errors',1);

$ports = [3306, 3307, 3308]; // probamos puertos típicos
$results = [];

foreach ($ports as $port) {
  $dsn = "mysql:host=127.0.0.1;port=$port;dbname=clients_app;charset=utf8";
  try {
    $t0 = microtime(true);
    $pdo = new PDO($dsn, 'root', '', [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_TIMEOUT => 2
    ]);
    $dt = round((microtime(true)-$t0)*1000);
    $results[] = "✅ Conectado OK en puerto $port ($dt ms)";
    // pequeño query
    $row = $pdo->query("SELECT 1 AS ok")->fetch();
    $results[] = "SELECT 1 => " . json_encode($row);
    break; // ya encontramos un puerto que funciona
  } catch (Throwable $e) {
    $results[] = "❌ Falló puerto $port: " . $e->getMessage();
  }
}

echo "<pre>".implode("\n", $results)."</pre>";