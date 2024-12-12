<?php
namespace App\Controllers;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Data\QRMatrix;

class CtrlGenerateMachineQR
{
    public function generateQR($request, $response, $container)
    {
        $machineId = $request->getParam('id');
        error_log("ID de máquina recibido: " . $machineId);

        if ($machineId) {
            $data = 'localhost/machinedetail/' . $machineId; // Cambia esto por la URL real de la ficha de la máquina
            $options = new QROptions([
                'version'    => 7,
                'outputType' => QRCode::OUTPUT_MARKUP_SVG,
                'eccLevel'   => QRCode::ECC_L,
                'svgViewBox' => true,
                'drawCircularModules' => true,
                'circleRadius' => 0.4,
                'connectPaths' => true,
                'keepAsSquare' => [
                    QRMatrix::M_FINDER_DARK,
                    QRMatrix::M_FINDER_DOT,
                    QRMatrix::M_ALIGNMENT_DARK,
                ],
                'svgDefs' => '
                    <linearGradient id="rainbow" x1="1" y2="1">
                        <stop stop-color="#000" offset="1"/>
                    </linearGradient>
                    <style><![CDATA[
                        .dark{fill: url(#rainbow);}
                        .light{fill: #eee;}
                    ]]></style>
                ',
            ]);

            $qrcode = new QRCode($options);
            $svg = $qrcode->render($data);
           // Codificar el SVG en base64
           $base64Svg = base64_encode($svg);
           $dataUri = $svg;

           // Mostrar el SVG como imagen
           echo '<img src="' . $dataUri . '" alt="QR Code" style="transform: scale(0.4); transform-origin: top center; display: block; margin: 0 auto; max-height: 100vh;"/>';
           exit; 
           
        } else {
            // Manejar el caso en que no se proporciona un ID de máquina
            $response->set('error', "ID de máquina no proporcionado.");
            $response->setTemplate('error.php');
            return $response;
        }
    }
}
?>