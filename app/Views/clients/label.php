<?php /** @var App\Models\Client $client */ /** @var array $sender */ ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Etiqueta #<?= (int)$client->id ?></title>
  <style>
    /* Papel 10cm x 14cm (vertical) */
    @page { size: 10cm 14cm; margin: 0; }
    @media print {
      html, body { margin:0; padding:0; }
      .no-print { display:none !important; }
      .sheet { box-shadow:none; margin:0; }
    }

    /* Base */
    *{ box-sizing:border-box }
    body{
      font-family: Arial, Helvetica, sans-serif;
      background:#f5f5f5;
      margin:10px;               /* solo en pantalla */
      color:#000;
    }
    .sheet{
      width:10cm; height:14cm;
      background:#fff;
      border:2px solid #000;
      padding:6mm 6mm 5mm;
      margin:auto;
      box-shadow:0 10px 24px rgba(0,0,0,.12);
      display:flex; flex-direction:column; gap:6px;
    }

    /* Encabezado con logo */
    .header{ text-align:center; padding-bottom:2mm; }
    .logo{ max-width:30%; max-height:15mm; object-fit:contain; }

    /* “Código de barras” decorativo */
  
    

    /* Secciones */
    .section{ border-top:1px solid #000; padding-top:3mm; font-size:12px; line-height:1.35; }
    .section h3{ margin:0 0 2mm 0; font-size:12px; letter-spacing:.2px; }
    .row{ display:block; margin-bottom:2px; }
    .label{ width:90px; display:inline-block; font-weight:bold; }

    /* Franja inferior (DESPACHA / PESO / BULTOS) */
    .important{ margin-top:auto; border-top:1px solid #000; padding-top:2mm; display:grid;
      grid-template-columns: 1.2fr .8fr .6fr; gap:4mm; align-items:stretch; }
    .ibox{padding:3mm; text-align:center; }
    .ttl{ font-size:10px; font-weight:bold; letter-spacing:.2px; margin-bottom:.5mm; }
    .val{ font-size:18px; font-weight:800; }
    .mono{ font-variant-numeric:tabular-nums; }

    /* Botones pantalla */
    .actions{ text-align:center; margin-bottom:6px }
    .actions a, .actions button{
      padding:6px 10px; border:1px solid #999; background:#fafafa;
      border-radius:6px; text-decoration:none; color:#000; font-size:.9rem; margin:0 4px;
    }
  </style>
</head>
<body>
  <div class="actions no-print">
  <button onclick="window.print()">🖨️ Imprimir</button>
  <a href="index.php?action=index">Volver</a>
  <a href="index.php?action=pdf&id=<?= (int)$client->id ?>">💾 Guardar PDF</a>
</div>
<div class="sheet">

  <!-- Acciones (ocultas al imprimir) -->
  

  <!-- Encabezado con LOGO (poner tu logo en /public/logo.png) -->
  <div class="header">
    <img class="logo" src="../app/Views/image/logo.png" alt="Logo">
  </div>


  <!-- REMITENTE -->
  <div class="section">
    <h3>REMITENTE:</h3>
    <div class="row"><span class="label">Nombre</span>: <?= htmlspecialchars($sender['nombre'].' '.$sender['apellido']) ?></div>
    <div class="row"><span class="label">DNI</span>: <span class="mono"><?= htmlspecialchars($sender['dni']) ?></span></div>
    <div class="row"><span class="label">Dirección</span>: <span class="mono"><?= htmlspecialchars($sender['localidad']) ?></span></div>
    <div class="row"><span class="label">CP</span>: <span class="mono"><?= htmlspecialchars($sender['cp']) ?></span></div>
    <div class="row"><span class="label">Teléfono</span>: <span class="mono"><?= htmlspecialchars($sender['telefono']) ?></span></div>
    <div class="row"><span class="label">Email</span>: <span class="mono"><?= htmlspecialchars($sender['email']) ?></span></div>
  </div>

  <!-- DESTINATARIO -->
  <div class="section">
    <h3>DESTINATARIO:</h3>
    <div class="row"><span class="label">Nombre</span>: <?= htmlspecialchars($client->nombre.' '.$client->apellido) ?></div>
    <div class="row"><span class="label">DNI</span>: <span class="mono"><?= htmlspecialchars($client->dni) ?></span></div>
    <div class="row"><span class="label">Dirección</span>: <?= htmlspecialchars($client->domicilio) ?></div>
    <div class="row"><span class="label">Provincia</span>: <?= htmlspecialchars($client->provincia) ?></div>
    <div class="row"><span class="label">Localidad</span>: <?= htmlspecialchars($client->localidad) ?></div>
    <div class="row"><span class="label">CP</span>: <span class="mono"><?= htmlspecialchars($client->cp) ?></span></div>
    <div class="row"><span class="label">Teléfono</span>: <span class="mono"><?= htmlspecialchars($client->telefono) ?></span></div>
    <div class="row"><span class="label">Email</span>: <span class="mono"><?= htmlspecialchars($client->email) ?></span></div>
  </div>

  <!-- INFO IMPORTANTE -->
  <div class="important">
    <div class="ibox">
      <div class="ttl">DESPACHA POR</div>
      <div class="val"><?= htmlspecialchars(mb_strtoupper($client->transporte, 'UTF-8')) ?></div>
    </div>
    <div class="ibox">
      <div class="ttl">PESO (kg)</div>
      <div class="val mono"><?= htmlspecialchars($client->peso !== '' ? str_replace('.', ',', $client->peso) : '—') ?></div>
    </div>
    <div class="ibox">
      <div class="ttl">BULTOS</div>
      <div class="val mono"><?= htmlspecialchars($client->bulto) ?></div>
    </div>
  </div>

</div>
</body>
</html>
