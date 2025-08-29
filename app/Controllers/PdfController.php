<?php
namespace App\Controllers;

use App\Repositories\ClientRepository;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfController
{
    /** @var ClientRepository */
    private $repo;
    /** @var array */
    private $sender;
    /** @var string */
    private $basePath;

    public function __construct(ClientRepository $repo, array $sender)
    {
        $this->repo     = $repo;
        // Datos fijos del remitente
        $this->sender   = $sender;
        // Raíz del proyecto (app/Controllers/../../)
        $this->basePath = realpath(__DIR__ . '/../../') ?: dirname(__DIR__, 2);
    }

    /**
     * Soporta: index.php?action=pdf&id=XX&download=0|1
     * - Guarda siempre en public/pdfs/etiqueta_{id}.pdf
     * - Si download=1 -> fuerza descarga
     * - Si no -> muestra inline el PDF
     */
    public function etiqueta()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) { http_response_code(400); echo 'ID inválido'; return; }

        // Repo compatible: getById() o findById()
        if (method_exists($this->repo, 'getById')) {
            $client = $this->repo->getById($id);
        } elseif (method_exists($this->repo, 'findById')) {
            $client = $this->repo->findById($id);
        } else {
            $client = null;
        }

        if (!$client) { http_response_code(404); echo 'Cliente no encontrado'; return; }

        // Logo -> data URI
        $logoPath   = $this->findLogoPath();
        $logoDataUri= $this->pathToDataUri($logoPath);

        // Variables EXACTAS que usás en tu plantilla
        $__client = $client;
        $__sender = $this->sender;
        $__logo   = $logoDataUri;

        // Elegí la vista que ya venías usando: label_pdf.php si existe, sino label.php
        $tpl = $this->resolveLabelTemplate();

        // Render HTML
        ob_start();
        include $tpl;
        $html = ob_get_clean();

        // Dompdf configuration
        $opts = new Options();
        $opts->set('isRemoteEnabled', true);
        $opts->set('isHtml5ParserEnabled', true);
        $opts->set('isPhpEnabled', true);
        $opts->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($opts);
        $dompdf->loadHtml($html, 'UTF-8');

        // Papel 10x14 cm en puntos (1 cm = 28.3464567 pt aprox)
        $w = 10 * 28.346; // 283.46 pt
        $h = 14 * 28.346; // 396.84 pt
        $dompdf->setPaper([0, 0, $w, $h], 'portrait');

        $dompdf->render();

        // Guardar a disco para merge/A4
        $outDir = $this->basePath . '/public/pdfs';
        if (!is_dir($outDir)) { @mkdir($outDir, 0777, true); }
        $filename = 'etiqueta_' . (int)$client->id . '.pdf';
        $full     = $outDir . '/' . $filename;
        file_put_contents($full, $dompdf->output());

        // Descargar o mostrar inline
        $download = !empty($_GET['download']) && $_GET['download'] === '1';
        if ($download) {
            $dompdf->stream($filename, ['Attachment' => true]);
            return;
        }

        // Mostrar inline el PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        echo $dompdf->output();
    }

    // =======================
    // Helpers privados
    // =======================

    /**
     * Busca el logo en ubicaciones conocidas manteniendo tu convención:
     * - app/Views/image/logo.png (preferido)
     * - public/logo.{png,jpg,jpeg,svg,PNG,JPG}
     */
    private function findLogoPath()
    {
        // 1) Ubicación tradicional dentro de app/Views/image/logo.png
        $p1 = $this->basePath . '/app/Views/image/logo.png';
        if (is_file($p1)) return $p1;

        // 2) Variantes en /public
        $candidates = [
            $this->basePath . '/public/logo.png',
            $this->basePath . '/public/logo.jpg',
            $this->basePath . '/public/logo.jpeg',
            $this->basePath . '/public/logo.svg',
            $this->basePath . '/public/logo.PNG',
            $this->basePath . '/public/logo.JPG',
        ];
        foreach ($candidates as $p) {
            if (is_file($p)) return $p;
        }
        return null;
    }

    /**
     * Convierte un archivo local a data URI (png/jpg/svg). Si no existe, devuelve null.
     */
    private function pathToDataUri($absPath)
    {
        if (!$absPath || !is_file($absPath)) return null;

        $data = @file_get_contents($absPath);
        if ($data === false) return null;

        // Detección MIME
        $mime = 'image/png';
        if (function_exists('finfo_open')) {
            $f = finfo_open(FILEINFO_MIME_TYPE);
            $m = finfo_file($f, $absPath);
            if ($m) $mime = $m;
            @finfo_close($f);
        } else {
            $ext = strtolower(pathinfo($absPath, PATHINFO_EXTENSION));
            if ($ext === 'svg')       $mime = 'image/svg+xml';
            elseif ($ext === 'jpg' || $ext === 'jpeg') $mime = 'image/jpeg';
            else                      $mime = 'image/png';
        }

        return 'data:' . $mime . ';base64,' . base64_encode($data);
    }

    /**
     * Devuelve la ruta al template de etiqueta.
     * Prioriza label_pdf.php si existe, sino usa label.php
     */
    private function resolveLabelTemplate()
    {
        $tplPdf = $this->basePath . '/app/Views/clients/label_pdf.php';
        if (is_file($tplPdf)) return $tplPdf;

        $tpl = $this->basePath . '/app/Views/clients/label.php';
        return $tpl;
    }
}
