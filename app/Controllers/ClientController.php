<?php
namespace App\Controllers;

use App\Repositories\ClientRepository;

class ClientController
{
    /** @var ClientRepository */
    private $repo;

    public function __construct(ClientRepository $repo)
    {
        $this->repo = $repo;
    }

    /** LISTADO (ya lo tenés; si no, dejalo como te pasé antes) */
    public function index()
    {
        $q          = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
        $transporte = isset($_GET['transporte']) ? trim((string)$_GET['transporte']) : '';
        $page       = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage    = isset($_GET['per_page']) ? max(5, min(100, (int)$_GET['per_page'])) : 30;

        $total   = $this->repo->countFiltered($q, $transporte);
        $pages   = max(1, (int)ceil($total / $perPage));
        if ($page > $pages) { $page = $pages; }

        $offset  = ($page - 1) * $perPage;
        $clients = $this->repo->findFiltered($q, $transporte, $perPage, $offset);

        $qs = $_GET; unset($qs['page']);
        $baseQuery = http_build_query($qs);

        $viewVars = [
            'clients'     => $clients,
            'q'           => $q,
            'transporte'  => $transporte,
            'page'        => $page,
            'perPage'     => $perPage,
            'total'       => $total,
            'pages'       => $pages,
            'baseQuery'   => $baseQuery ? $baseQuery.'&' : '',
        ];
        extract($viewVars);
        require __DIR__ . '/../Views/clients/index.php';
    }

    /** NUEVO: muestra formulario vacío */
    public function create()
    {
        $errors = [];
        $old = []; // sin datos
        require __DIR__ . '/../Views/clients/form.php';
    }

    /** GUARDAR NUEVO */
    public function store()
    {
        $errors = [];
        $old = $_POST;

        // Validación mínima: solo nombre requerido
        if (empty($old['nombre'])) {
            $errors['nombre'] = 'El nombre es obligatorio.';
        }

        if (!empty($old['email']) && !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido.';
        }

        if ($errors) {
            // Volver al formulario con errores
            require __DIR__ . '/../Views/clients/form.php';
            return;
        }

        // Normalizar valores opcionales
        foreach (['apellido','dni','domicilio','provincia','localidad','cp','telefono','email','transporte','peso','bulto'] as $k) {
            if (!isset($old[$k])) $old[$k] = '';
        }
        if ($old['bulto'] === '') $old['bulto'] = '1';

        $this->repo->insert($old);
        header('Location: index.php?action=index&saved=1');
        exit;
    }

    /** EDITAR (carga por id y muestra formulario) */
    public function edit()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) { header('Location: index.php?action=index'); exit; }

        $row = $this->repo->getById($id);
        if (!$row) { header('Location: index.php?action=index'); exit; }

        $errors = [];
        // Convertir stdClass a array simple para reutilizar `form.php`
        $old = [
            'id'         => $row->id,
            'nombre'     => $row->nombre,
            'apellido'   => $row->apellido,
            'dni'        => $row->dni,
            'domicilio'  => $row->domicilio,
            'provincia'  => $row->provincia,
            'localidad'  => $row->localidad,
            'cp'         => $row->cp,
            'telefono'   => $row->telefono,
            'email'      => $row->email,
            'transporte' => $row->transporte,
            'peso'       => $row->peso,
            'bulto'      => $row->bulto,
        ];
        require __DIR__ . '/../Views/clients/form.php';
    }

    /** ACTUALIZAR */
    public function update()
    {
        $errors = [];
        $old = $_POST;

        $id = isset($old['id']) ? (int)$old['id'] : 0;
        if ($id <= 0) { header('Location: index.php?action=index'); exit; }

        if (empty($old['nombre'])) {
            $errors['nombre'] = 'El nombre es obligatorio.';
        }
        if (!empty($old['email']) && !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido.';
        }

        if ($errors) {
            require __DIR__ . '/../Views/clients/form.php';
            return;
        }

        foreach (['apellido','dni','domicilio','provincia','localidad','cp','telefono','email','transporte','peso','bulto'] as $k) {
            if (!isset($old[$k])) $old[$k] = '';
        }
        if ($old['bulto'] === '') $old['bulto'] = '1';

        $this->repo->update($id, $old);
        header('Location: index.php?action=index&updated=1');
        exit;
    }

    /** ELIMINAR */
    public function delete()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) { $this->repo->delete($id); }
        header('Location: index.php?action=index&deleted=1');
        exit;
    }
}
