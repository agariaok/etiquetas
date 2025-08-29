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
  <link rel="stylesheet" href="assets/css/form.css?v=1">
</head>
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
