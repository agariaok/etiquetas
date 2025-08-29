<?php
/** @var App\Models\Client $__client */
/** @var array $__sender */
/** @var string|null $__logo */

// Helper para MAYÚSCULAS seguras (con acentos) + escape HTML
$U = function($s){
  return htmlspecialchars(mb_strtoupper((string)$s, 'UTF-8'));
};
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Etiqueta #<?= (int)$__client->id ?></title>
<style>
  @page { margin: 0; }
  html, body { margin:0; padding:0; }
  body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 8pt; color:#000; }

  /* Página exacta 10x14 cm (ajuste actual tuyo) */
  .page   { width: 96mm; height: 100mm; }
  .frame  { width: 96mm; height: 100mm; }

  /* Paddings y separadores */
  .inpad  { padding: 3mm; }
  .sep1   { height: 2mm; }
  .sep2   { height: 1.5mm; }

  /* Tipografía */
  .h      { font-weight: bold; font-size: 10pt; }
  .mono   { font-variant-numeric: tabular-nums; }
  .kcell  { width: 28mm; font-weight: bold; vertical-align: top; }

  /* Contenido: que no rompa con textos largos */
  td { word-break: break-word; }

  /* Cajas inferiores */
  .ibox   { border: 0.6pt solid #000; }
  .ttl    { font-size: 7.8pt; font-weight: bold; }
  .val    { font-size: 12pt; font-weight: 800; }

  /* Logo */
  .logoImg    { max-height: 12mm; height:auto; }
</style>
</head>
<body>
<table class="page" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="middle">
      <table class="frame" cellspacing="0" cellpadding="0">
        <tr>
          <td class="inpad">

            <!-- LOGO -->
            <table width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center">
                  <?php if ($__logo): ?>
                    <img src="<?= $__logo ?>" alt="Logo" class="logoImg">
                  <?php endif; ?>
                </td>
              </tr>
            </table>

            <table width="100%" cellspacing="0" cellpadding="0"><tr><td class="sep1"></td></tr></table>

            <!-- REMITENTE -->
            <table width="100%" cellspacing="0" cellpadding="0" style="border-top:0.6pt solid #000;">
              <tr><td class="sep2"></td></tr>
              <tr><td class="h">REMITENTE:</td></tr>
              <tr><td class="sep2"></td></tr>
            </table>

            <table width="100%" cellspacing="0" cellpadding="1">
              <tr><td class="kcell">Nombre</td><td><?= $U($__sender['nombre'].' '.$__sender['apellido']) ?></td></tr>
              <tr><td class="kcell">DNI</td><td class="mono"><?= htmlspecialchars($__sender['dni']) ?></td></tr>
              <tr><td class="kcell">Dirección</td><td><?= $U($__sender['localidad']) ?></td></tr>
              <tr><td class="kcell">CP</td><td class="mono"><?= htmlspecialchars($__sender['cp']) ?></td></tr>
              <tr><td class="kcell">Teléfono</td><td class="mono"><?= htmlspecialchars($__sender['telefono']) ?></td></tr>
              <tr><td class="kcell">Email</td><td class="mono"><?= $U($__sender['email']) ?></td></tr>
            </table>

            <!-- DESTINATARIO -->
            <table width="100%" cellspacing="0" cellpadding="0" style="border-top:0.6pt solid #000;">
              <tr><td class="sep2"></td></tr>
              <tr><td class="h">DESTINATARIO:</td></tr>
              <tr><td class="sep2"></td></tr>
            </table>

            <table width="100%" cellspacing="0" cellpadding="1">
              <tr><td class="kcell">Nombre</td><td><?= $U($__client->nombre.' '.$__client->apellido) ?></td></tr>
              <tr><td class="kcell">DNI</td><td class="mono"><?= htmlspecialchars($__client->dni) ?></td></tr>
              <tr><td class="kcell">Dirección</td><td><?= $U($__client->domicilio) ?></td></tr>
              <tr><td class="kcell">Provincia</td><td><?= $U($__client->provincia) ?></td></tr>
              <tr><td class="kcell">Localidad</td><td><?= $U($__client->localidad) ?></td></tr>
              <tr><td class="kcell">CP</td><td class="mono"><?= htmlspecialchars($__client->cp) ?></td></tr>
              <tr><td class="kcell">Teléfono</td><td class="mono"><?= htmlspecialchars($__client->telefono) ?></td></tr>
              <tr><td class="kcell">Email</td><td class="mono"><?= $U($__client->email) ?></td></tr>
            </table>

            <table width="100%" cellspacing="0" cellpadding="0"><tr><td class="sep1" style="border-top:0.6pt solid #000;"></td></tr></table>

            <!-- DESPACHA / PESO / BULTOS -->
            <table width="100%" cellspacing="2" cellpadding="3">
              <tr>
                <td width="44%">
                  <table width="100%" cellspacing="0" cellpadding="3">
                    <tr>
                      <td align="center">
                        <div class="ttl">DESPACHA POR</div>
                        <div class="val"><?= $U($__client->transporte) ?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td width="28%">
                  <table width="100%" cellspacing="0" cellpadding="3">
                    <tr>
                      <td align="center">
                        <div class="ttl">PESO (kg)</div>
                        <div class="val mono"><?= htmlspecialchars($__client->peso !== '' ? str_replace('.', ',', $__client->peso) : '—') ?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td width="28%">
                  <table width="100%" cellspacing="0" cellpadding="3">
                    <tr>
                      <td align="center">
                        <div class="ttl">BULTOS</div>
                        <div class="val mono"><?= htmlspecialchars($__client->bulto) ?></div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
