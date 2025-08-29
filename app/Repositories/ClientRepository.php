<?php
namespace App\Repositories;

use PDO;

class ClientRepository
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /** Lista paginada con filtros */
    public function findFiltered($q = '', $transporte = '', $limit = 30, $offset = 0)
    {
        $sql  = "SELECT * FROM clients WHERE 1=1";
        $args = [];

        if ($q !== '') {
            $sql .= " AND (nombre LIKE :q OR apellido LIKE :q OR dni LIKE :q OR email LIKE :q OR telefono LIKE :q)";
            $args[':q'] = "%".$q."%";
        }
        if ($transporte !== '') {
            $sql .= " AND LOWER(transporte) = :t";
            $args[':t'] = strtolower($transporte);
        }

        $sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);

        foreach ($args as $k => $v) { $stmt->bindValue($k, $v); }
        $stmt->bindValue(':limit',  (int)$limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ); // objetos genéricos
    }

    /** Total para paginación */
    public function countFiltered($q = '', $transporte = '')
    {
        $sql  = "SELECT COUNT(*) FROM clients WHERE 1=1";
        $args = [];

        if ($q !== '') {
            $sql .= " AND (nombre LIKE :q OR apellido LIKE :q OR dni LIKE :q OR email LIKE :q OR telefono LIKE :q)";
            $args[':q'] = "%".$q."%";
        }
        if ($transporte !== '') {
            $sql .= " AND LOWER(transporte) = :t";
            $args[':t'] = strtolower($transporte);
        }

        $stmt = $this->pdo->prepare($sql);
        foreach ($args as $k => $v) { $stmt->bindValue($k, $v); }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /** CRUD básico */
    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM clients WHERE id = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject(); // stdClass o false
    }

    public function insert(array $data)
    {
        $sql = "INSERT INTO clients
        (nombre, apellido, dni, domicilio, provincia, localidad, cp, telefono, email, transporte, peso, bulto)
        VALUES
        (:nombre, :apellido, :dni, :domicilio, :provincia, :localidad, :cp, :telefono, :email, :transporte, :peso, :bulto)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nombre',     (string)($data['nombre'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':apellido',   (string)($data['apellido'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':dni',        (string)($data['dni'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':domicilio',  (string)($data['domicilio'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':provincia',  (string)($data['provincia'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':localidad',  (string)($data['localidad'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':cp',         (string)($data['cp'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':telefono',   (string)($data['telefono'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':email',      (string)($data['email'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':transporte', (string)($data['transporte'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':peso',       ($data['peso'] === '' ? null : (string)$data['peso']), $data['peso'] === '' ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':bulto',      (string)($data['bulto'] ?? '1'), PDO::PARAM_STR);

        $stmt->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function update($id, array $data)
    {
        $sql = "UPDATE clients SET
            nombre=:nombre, apellido=:apellido, dni=:dni, domicilio=:domicilio,
            provincia=:provincia, localidad=:localidad, cp=:cp, telefono=:telefono,
            email=:email, transporte=:transporte, peso=:peso, bulto=:bulto
        WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id',         (int)$id, PDO::PARAM_INT);
        $stmt->bindValue(':nombre',     (string)($data['nombre'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':apellido',   (string)($data['apellido'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':dni',        (string)($data['dni'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':domicilio',  (string)($data['domicilio'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':provincia',  (string)($data['provincia'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':localidad',  (string)($data['localidad'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':cp',         (string)($data['cp'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':telefono',   (string)($data['telefono'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':email',      (string)($data['email'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':transporte', (string)($data['transporte'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':peso',       ($data['peso'] === '' ? null : (string)$data['peso']), $data['peso'] === '' ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':bulto',      (string)($data['bulto'] ?? '1'), PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM clients WHERE id = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
