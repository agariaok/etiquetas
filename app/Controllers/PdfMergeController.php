<?php
namespace App\Controllers;

use setasign\Fpdi\Fpdi;

class PdfMergeController
{
    private $basePath;

    public function __construct()
    {
        $this->basePath = realpath(__DIR__ . '/../../'); // raíz del proyecto
    }

    /**
     * Combina PDFs existentes en una hoja A4.
     * Espera POST: ids[]  (IDs de clientes)
     *             layout  ('2x2' | '1x3' | '2x3')
     *             download ('0'|'1')
     */
    public function merge()
    {
        $ids = isset($_POST['ids']) && is_array($_POST['ids']) ? array_map('intval', $_POST['ids']) : [];
        if (!$ids) {
            http_response_code(400);
            echo '<div style="font-family:system-ui;margin:24px">No seleccionaste etiquetas.</div>';
            return;
        }

        // Layouts disponibles (en mm, posiciones X,Y por slot)
        $layouts = [
            // 2 columnas × 2 filas (4 por A4). Cada etiqueta 100x140 mm; margen ~5 mm
            '2x2' => [
                'slots' => [[5,5], [105,5], [5,152], [105,152]],
                'w' => 100, 'h' => 140
            ],
            // 1 columna × 3 filas (3 por hoja)
            '1x3' => [
                'slots' => [[20,8], [20,103], [20,198]],
                'w' => 170, 'h' => 90
            ],
            // 2 columnas × 3 filas (6 por hoja)
            '2x3' => [
                'slots' => [[10,10],[110,10],[10,105],[110,105],[10,200],[110,200]],
                'w' => 95, 'h' => 90
            ],
        ];

        $layoutKey = isset($_POST['layout']) && isset($layouts[$_POST['layout']]) ? $_POST['layout'] : '2x2';
        $layout = $layouts[$layoutKey];

        // Crear PDF A4
        $pdf = new Fpdi('P', 'mm', 'A4');
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false);

        $slotIndex = 0;
        $slotsPerPage = count($layout['slots']);

        foreach ($ids as $id) {
            $file = $this->basePath . '/public/pdfs/etiqueta_' . $id . '.pdf';
            if (!is_file($file)) {
                // si no existe, lo saltamos
                continue;
            }

            if ($slotIndex % $slotsPerPage === 0) {
                $pdf->AddPage(); // nueva hoja A4
            }

            // Importa primera página del PDF de la etiqueta
            $pdf->setSourceFile($file);
            $tpl = $pdf->importPage(1);

            list($x, $y) = $layout['slots'][$slotIndex % $slotsPerPage];
            $pdf->useTemplate($tpl, $x, $y, $layout['w'], $layout['h']); // escala a tamaño del layout

            $slotIndex++;
        }

        // Si no se colocó nada (por ejemplo, PDFs inexistentes)
        if ($slotIndex === 0) {
            http_response_code(404);
            echo '<div style="font-family:system-ui;margin:24px">No se encontró ningún PDF para los IDs seleccionados.</div>';
            return;
        }

        // Salida
        $filename = 'etiquetas_A4_' . date('Ymd_His') . '.pdf';
        $download = isset($_POST['download']) && $_POST['download'] === '1';

        if ($download) {
            $pdf->Output('D', $filename); // descarga
        } else {
            $pdf->Output('I', $filename); // ver en navegador
        }
    }
}
