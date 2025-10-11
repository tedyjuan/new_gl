<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf
{
    public function create($html, $filename = 'document.pdf', $paper = 'A4', $orientation = 'portrait', $stream = TRUE)
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();

        if ($stream) {
            $dompdf->stream($filename, array("Attachment" => false));
        } else {
            return $dompdf->output();
        }
    }
}
