<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;
use App\Repositories\ClientRepository;
use App\Controllers\ClientController;
use App\Controllers\PdfController;
use App\Controllers\PdfMergeController;
use App\Controllers\BulkController; // si lo usás

error_reporting(E_ALL);
ini_set('display_errors', '1');

// ===== Config de la app =====
$config = [
    'sender' => [
        'nombre'   => 'INGRID',
        'apellido' => 'Miño',
        'dni'      => '94597165',
        'localidad'=> 'CABA',
        'cp'       => '1437',
        'telefono' => '1130850303',
        'email'    => 'agaria.ok@gmail.com',
    ],
    'logo'    => realpath(__DIR__ . '/../app/Views/image/logo.png') ?: null,
    'pdf_dir' => realpath(__DIR__ . '/pdfs') ?: (__DIR__ . '/pdfs'),
];
if (!is_dir($config['pdf_dir'])) { @mkdir($config['pdf_dir'], 0777, true); }

// ===== DB + Repo =====
$pdo  = Database::pdo();
$repo = new ClientRepository($pdo);

// ===== Routing =====
$action = isset($_GET['action']) ? (string)$_GET['action'] : 'index';

try {
    switch ($action) {
        case 'index':
        default:
            (new ClientController($repo))->index();
            break;

        case 'create':
            (new ClientController($repo))->create();
            break;

        case 'store':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php?action=index'); exit; }
            (new ClientController($repo))->store();
            break;

        case 'edit':
            (new ClientController($repo))->edit();
            break;

        case 'update':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php?action=index'); exit; }
            (new ClientController($repo))->update();
            break;

        case 'delete':
            (new ClientController($repo))->delete();
            break;

        // ===== PDF individual =====
        case 'pdf':
            $pdf = new PdfController($repo, $config['sender'], $config['logo'], $config['pdf_dir']);
            if (method_exists($pdf, 'etiquetaForId') && isset($_GET['id'])) {
                $pdf->etiquetaForId((int)$_GET['id'], !empty($_GET['download']));
            } else {
                $pdf->etiqueta();
            }
            break;

        // ===== Merge A4 (FPDI) =====
        case 'merge':
            // POST: ids[], layout (2x2|1x3|2x3), download (0|1)
            $merge = new PdfMergeController($repo, $config['sender'], $config['logo'], $config['pdf_dir']);
            $merge->merge();
            break;

       
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo '<!doctype html><meta charset="utf-8"><style>body{font-family:system-ui;margin:24px}</style>';
    echo '<h2>Error</h2><pre style="white-space:pre-wrap;background:#f6f8fa;padding:12px;border:1px solid #e5e7eb;border-radius:8px;">';
    echo htmlspecialchars($e->getMessage() . "\n\n" . $e->getTraceAsString(), ENT_QUOTES, 'UTF-8');
    echo '</pre>';
    exit;
}
