<?php /** @var array $errors */ /** @var array $old */ ?>
<?php
$provincias = [
  'Buenos Aires','Ciudad Autónoma de Buenos Aires (CABA)','Catamarca','Chaco','Chubut','Córdoba','Corrientes','Entre Ríos',
  'Formosa','Jujuy','La Pampa','La Rioja','Mendoza','Misiones','Neuquén','Río Negro','Salta','San Juan','San Luis',
  'Santa Cruz','Santa Fe','Santiago del Estero','Tierra del Fuego, Antártida e Islas del Atlántico Sur','Tucumán',
];
$provActual = $old['provincia'] ?? '';

$transportes = ['correo argentino','andreani','via cargo','comisionista','personal','micro','expreso'];
$transporteActual = $old['transporte'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= isset($old['id']) ? 'Editar' : 'Nuevo' ?> cliente</title>
  <style>
    *{ box-sizing: border-box; }
    .grid-item{ min-width: 0; }

    body{
      font-family:'Segoe UI', Roboto, Arial, sans-serif;
      margin:24px;
      background: linear-gradient(135deg,#f7f7f7,#eceff1);
      color:#222;
    }
    .card{
      background:#fff;
      border-radius:16px;
      padding:22px;
      max-width:950px;
      margin:auto;
      box-shadow:0 10px 24px rgba(0,0,0,.10);
      transition:.25s;
    }
    .card:hover{ box-shadow:0 14px 30px rgba(0,0,0,.14); }
    h2{
      margin:0 0 12px;
      font-size:1.35rem;
      border-bottom:2px solid #eef0f3;
      padding-bottom:10px;
    }

    form{
      display:grid;
      grid-template-columns: repeat(3, 1fr);
      gap:16px 18px;
      margin-top:10px;
    }
    .full{ grid-column: 1 / -1; }
    .grid-item{ display:flex; flex-direction:column; gap:6px; }
    label{ font-weight:600; font-size:.92rem; color:#333; }

    input, select{
      display:block; width:100%; max-width:100%;
      padding:10px 12px; border:1px solid #cfd8e3; border-radius:10px;
      background:#fafafa; font-size:.95rem;
      transition:border-color .2s, box-shadow .2s, background .2s;
    }
    input:hover, select:hover{ border-color:#9fb3c8; }
    input:focus, select:focus{
      outline:none; background:#fff; border-color:#1976d2; box-shadow:0 0 0 3px rgba(25,118,210,.18);
    }

    .error{ color:#d32f2f; font-size:.82rem; min-height:16px; }

    .actions{
      grid-column: 1 / -1;
      display:flex; align-items:center; justify-content:center; gap:12px;
      margin-top:8px;
    }
    .btn{
      min-width:160px; height:42px; padding:0 18px; border-radius:10px; cursor:pointer;
      font-weight:600; font-size:.95rem; text-decoration:none; display:inline-flex; align-items:center; justify-content:center;
      border:0; transition:.2s;
    }
    .btn.primary{ background:#1976d2; color:#fff; }
    .btn.primary:hover{ background:#145aa6; box-shadow:0 3px 8px rgba(0,0,0,.15); }
    .btn.secondary{ background:#f5f7f9; color:#333; border:1px solid #d5dbe3; text-decoration:none; }
    .btn.secondary:hover{ background:#e9edf2; }

    @media (max-width: 760px){
      form{ grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <div class="card">
    <h2><?= isset($old['id']) ? 'Editar' : 'Nuevo' ?> cliente</h2>

    <form method="post" action="index.php?action=<?= isset($old['id']) ? 'update' : 'store' ?>">
      <?php if (!empty($old['id'])): ?>
        <input type="hidden" name="id" value="<?= (int)$old['id'] ?>">
      <?php endif; ?>

      <div class="grid-item">
        <label>Nombre</label>
        <input name="nombre" value="<?= htmlspecialchars($old['nombre'] ?? '') ?>" required>
        <div class="error"><?= $errors['nombre'] ?? '' ?></div>
      </div>

      <div class="grid-item">
        <label>Apellido</label>
        <input name="apellido" value="<?= htmlspecialchars($old['apellido'] ?? '') ?>">
        <div class="error"><?= $errors['apellido'] ?? '' ?></div>
      </div>

      <div class="grid-item">
        <label>DNI</label>
        <input name="dni" value="<?= htmlspecialchars($old['dni'] ?? '') ?>">
        <div class="error"><?= $errors['dni'] ?? '' ?></div>
      </div>

      <div class="grid-item full">
        <label>Domicilio</label>
        <input name="domicilio" value="<?= htmlspecialchars($old['domicilio'] ?? '') ?>">
        <div class="error"><?= $errors['domicilio'] ?? '' ?></div>
      </div>

      <div class="grid-item">
        <label>Provincia</label>
        <select name="provincia">
          <option value="" <?= $provActual===''?'selected':'' ?> disabled>Seleccioná una provincia</option>
          <?php foreach ($provincias as $p): ?>
            <option value="<?= htmlspecialchars($p) ?>" <?= $provActual===$p ? 'selected' : '' ?>>
              <?= htmlspecialchars($p) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <div class="error"><?= $errors['provincia'] ?? '' ?></div>
      </div>

      <div class="grid-item">
        <label>Localidad</label>
        <input name="localidad" value="<?= htmlspecialchars($old['localidad'] ?? '') ?>">
        <div class="error"><?= $errors['localidad'] ?? '' ?></div>
      </div>

      <div class="grid-item">
        <label>Código Postal</label>
        <input name="cp" value="<?= htmlspecialchars($old['cp'] ?? '') ?>">
        <div class="error"><?= $errors['cp'] ?? '' ?></div>
      </div>

      <div class="grid-item">
        <label>Teléfono</label>
        <input name="telefono" value="<?= htmlspecialchars($old['telefono'] ?? '') ?>">
        <div class="error"><?= $errors['telefono'] ?? '' ?></div>
      </div>

      <div class="grid-item">
        <label>Email</label>
        <input name="email" type="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
        <div class="error"><?= $errors['email'] ?? '' ?></div>
      </div>

      <div class="grid-item">
        <label>Transporte</label>
        <select name="transporte">
          <option value="" <?= $transporteActual===''?'selected':'' ?> disabled>Seleccioná transporte</option>
          <?php foreach ($transportes as $t): ?>
            <option value="<?= htmlspecialchars($t) ?>" <?= $transporteActual===$t ? 'selected' : '' ?>>
              <?= htmlspecialchars(ucwords($t)) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <div class="error"><?= $errors['transporte'] ?? '' ?></div>
      </div>

      <div class="grid-item">
        <label>Peso (kg)</label>
        <input name="peso" placeholder="0.00" value="<?= htmlspecialchars($old['peso'] ?? '') ?>">
        <div class="error"><?= $errors['peso'] ?? '' ?></div>
      </div>

      <div class="grid-item">
        <label>Bulto (cantidad)</label>
        <input name="bulto" value="<?= htmlspecialchars($old['bulto'] ?? '1') ?>">
        <div class="error"><?= $errors['bulto'] ?? '' ?></div>
      </div>

      <div class="actions">
        <a class="btn secondary" href="index.php?action=index">Volver</a>
        <button class="btn primary" type="submit">Guardar</button>
      </div>
    </form>
  </div>
</body>
</html>
