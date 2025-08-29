<?php /** @var App\Models\Client[] $clients */ ?>
<?php
// variables que el controlador pasa a la vista:
// $q, $transporte, $page, $perPage, $total, $pages, $baseQuery
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Clientes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Enlace al CSS externo -->
  <link rel="stylesheet" href="assets/css/clients.css?v=1">
</head>
<body>
  <div class="card">
    <h1>Clientes</h1>

    <?php if (!empty($_GET['saved'])): ?>
      <div class="alert ok">✅ Cliente guardado</div>
    <?php elseif (!empty($_GET['updated'])): ?>
      <div class="alert ok">✅ Cliente actualizado</div>
    <?php elseif (!empty($_GET['deleted'])): ?>
      <div class="alert warn">🗑️ Cliente eliminado</div>
    <?php endif; ?>

    <!-- Filtros -->
    <form class="toolbar" method="get" action="index.php" id="filterForm">
      <input type="hidden" name="action" value="index">
      <input class="grow" name="q" placeholder="Buscar" value="<?= htmlspecialchars($q ?? '') ?>">
      <select name="transporte" title="Transporte">
        <option value="">— Transporte —</option>
        <?php
          $transportes = ['correo argentino','andreani','via cargo','comosionista','personal','micro','expreso'];
          $tSel = strtolower($transporte ?? '');
          foreach ($transportes as $t) {
            $sel = ($tSel === strtolower($t)) ? 'selected' : '';
            echo '<option '.$sel.'>'.htmlspecialchars($t).'</option>';
          }
        ?>
      </select>
      <select name="per_page" title="Por página">
        <?php foreach ([10,20,30,50,100] as $pp): ?>
          <option value="<?= $pp ?>" <?= ($perPage==$pp ? 'selected':'') ?>><?= $pp ?> / pág.</option>
        <?php endforeach; ?>
      </select>
      <button class="btn primary" type="submit">Filtrar</button>
      <a class="btn secondary" href="index.php?action=create">Nuevo cliente</a>
    </form>

    <!-- Selección múltiple + merge A4 -->
    <form method="post" action="index.php?action=merge" id="mergeForm">
      <table>
        <thead>
          <tr>
            <th style="width:40px"><input type="checkbox" id="checkAll"></th>
            <th>#</th>
            <th>Nombre</th>
            
            <th>Teléfono</th>
            <th>Provincia</th>
            <th>Transporte</th>
            <th class="muted">Peso/Bulto</th>
            <th style="width:360px">Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php if (empty($clients)): ?>
          <tr><td colspan="9" class="muted" style="padding:16px">No hay resultados.</td></tr>
        <?php else: foreach ($clients as $c): ?>
          <tr>
            <td data-label="Sel.">
              <input type="checkbox" name="ids[]" value="<?= (int)$c->id ?>" class="rowcheck">
            </td>
            <td data-label="#"> <?= (int)$c->id ?></td>
            <td data-label="Nombre">
              <?= htmlspecialchars($c->nombre.' '.$c->apellido) ?>
            </td>
            
            <td data-label="Teléfono"><?= htmlspecialchars($c->telefono) ?></td>
            <td data-label="Provincia"><?= htmlspecialchars($c->provincia) ?></td>
            <td data-label="Transporte"><span class=""><?= htmlspecialchars($c->transporte) ?></span></td>
            <td data-label="Peso/Bulto" class="muted">
              <?= htmlspecialchars(($c->peso === '' ? '—' : $c->peso.' kg').' / '.$c->bulto) ?>
            </td>
            <td data-label="Acciones">
              <div class="actions">
                <a class="btn secondary" href="index.php?action=pdf&id=<?= (int)$c->id ?>&download=1">⬇️ PDF</a>
                <a class="btn secondary" href="index.php?action=edit&id=<?= (int)$c->id ?>">✏️ Editar</a>
                <a class="btn danger" href="index.php?action=delete&id=<?= (int)$c->id ?>" onclick="return confirm('¿Eliminar este cliente?')">🗑️ Borrar</a>
              </div>
            </td>
          </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>

      <div class="bulkbar">
        <div class="checks">
          <label><input type="checkbox" id="checkAllBottom"> Seleccionar todos</label>
          <span class="info-sel" id="selInfo">0 seleccionados</span>
          <button type="button" class="btn secondary" id="clearSel" style="min-width:140px;height:40px;">🧹 Limpiar selección</button>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
          <select name="layout" class="btn secondary" style="min-width:160px;height:40px;padding:0 14px;">
            <option value="2x2">A4 (2×2) — 4 etiquetas</option>
            <option value="1x3">A4 (1×3) — 3 etiquetas grandes</option>
            <option value="2x3">A4 (2×3) — 6 etiquetas pequeñas</option>
          </select>
          <button class="btn primary" type="submit" name="download" value="0">🧩 Armar hoja A4</button>
          <button class="btn secondary" type="submit" name="download" value="1">⬇️ Descargar A4</button>
        </div>
      </div>
    </form>

    <!-- Paginación -->
    <?php if (($pages ?? 1) > 1): ?>
      <div class="pagination">
        <?php
          // Helper para link con query
          function pLink($p, $label, $active=false, $disabled=false, $baseQuery=''){
            $href = $disabled ? '#' : "index.php?{$baseQuery}action=index&page={$p}";
            $cls  = 'page-link'.($active?' active':'').($disabled?' disabled':'');
            echo "<a class=\"{$cls}\" href=\"{$href}\">{$label}</a>";
          }
          $bp = $baseQuery ?? '';
          $cur = $page ?? 1;
          $tot = $pages ?? 1;

          pLink(1, '« Primera', false, $cur<=1, $bp);
          pLink(max(1, $cur-1), '‹ Anterior', false, $cur<=1, $bp);

          // rango de páginas (máx 7 visibles)
          $range = 3;
          $start = max(1, $cur - $range);
          $end   = min($tot, $cur + $range);
          if ($start > 1) echo '<span class="page-link disabled">…</span>';
          for ($i=$start; $i<=$end; $i++){
            pLink($i, (string)$i, $i==$cur, false, $bp);
          }
          if ($end < $tot) echo '<span class="page-link disabled">…</span>';

          pLink(min($tot, $cur+1), 'Siguiente ›', false, $cur>=$tot, $bp);
          pLink($tot, 'Última »', false, $cur>=$tot, $bp);
        ?>
      </div>
    <?php endif; ?>
  </div>

  <script>
    // ----- Persistencia de selección entre filtros/paginación -----
    const STORAGE_KEY = 'selectedClientIds';

    const filterForm  = document.getElementById('filterForm');
    const mergeForm   = document.getElementById('mergeForm');

    const allTop      = document.getElementById('checkAll');
    const allBottom   = document.getElementById('checkAllBottom');
    const checks      = Array.from(document.querySelectorAll('.rowcheck'));
    const infoSel     = document.getElementById('selInfo');
    const clearSelBtn = document.getElementById('clearSel');

    function loadSelected(){
      try {
        const raw = localStorage.getItem(STORAGE_KEY);
        return raw ? new Set(JSON.parse(raw)) : new Set();
      } catch(e){ return new Set(); }
    }
    function saveSelected(set){ localStorage.setItem(STORAGE_KEY, JSON.stringify(Array.from(set))); }

    function applySelection(set){
      checks.forEach(ch => { ch.checked = set.has(ch.value); });
    }
    function updateInfo(set){
      const n = set.size; // total, incluídos no visibles
      infoSel.textContent = n + ' seleccionados';
      const nVisibleChecked = checks.filter(c => c.checked).length;
      const all = checks.length > 0 && nVisibleChecked === checks.length;
      if (allTop)    allTop.checked    = all;
      if (allBottom) allBottom.checked = all;
    }

    let selectedSet = loadSelected();
    applySelection(selectedSet);
    updateInfo(selectedSet);

    checks.forEach(ch => {
      ch.addEventListener('change', () => {
        if (ch.checked) selectedSet.add(ch.value);
        else            selectedSet.delete(ch.value);
        saveSelected(selectedSet);
        updateInfo(selectedSet);
      });
    });

    function toggleAll(src){
      checks.forEach(c => {
        c.checked = src.checked;
        if (c.checked) selectedSet.add(c.value);
        else           selectedSet.delete(c.value);
      });
      saveSelected(selectedSet);
      updateInfo(selectedSet);
    }
    allTop?.addEventListener('change', e => toggleAll(e.target));
    allBottom?.addEventListener('change', e => toggleAll(e.target));

    // Shift + click para rango sobre visibles
    let lastChecked = null;
    checks.forEach(chk => {
      chk.addEventListener('click', (e) => {
        if (!lastChecked) { lastChecked = chk; return; }
        if (e.shiftKey) {
          const arr = checks;
          let start = arr.indexOf(chk);
          let end   = arr.indexOf(lastChecked);
          if (start > end) { const t = start; start = end; end = t; }
          for (let i=start; i<=end; i++) {
            arr[i].checked = lastChecked.checked;
            if (arr[i].checked) selectedSet.add(arr[i].value);
            else                selectedSet.delete(arr[i].value);
          }
          saveSelected(selectedSet);
          updateInfo(selectedSet);
        }
        lastChecked = chk;
      });
    });

    // Enviar TODOS los IDs seleccionados (aunque no estén visibles)
    if (mergeForm) {
      mergeForm.addEventListener('submit', () => {
        Array.from(mergeForm.querySelectorAll('input[name="ids[]"].from-storage')).forEach(n => n.remove());
        selectedSet.forEach(id => {
          if (!checks.find(c => c.value === id && c.checked)) {
            const h = document.createElement('input');
            h.type = 'hidden';
            h.name = 'ids[]';
            h.value = id;
            h.className = 'from-storage';
            mergeForm.appendChild(h);
          }
        });
      });
    }

    // Limpiar selección
    clearSelBtn?.addEventListener('click', () => {
      selectedSet = new Set();
      saveSelected(selectedSet);
      checks.forEach(c => c.checked = false);
      updateInfo(selectedSet);
    });
  </script>
</body>
</html>
