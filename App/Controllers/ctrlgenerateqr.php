<?php
namespace App\Controllers;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Data\QRMatrix;

class CtrlGenerateMachineQR
{
    // Method to generate the QR code
    public function generateQR($request, $response, $container)
    {
        // Retrieve the machine ID from the request parameters
        $machineId = $request->getParam('id');
        error_log("Received machine ID: " . $machineId); // Log the machine ID for debugging

        // Check if the machine ID is provided
        if ($machineId) {
            // Build the URL for the machine details page
            $data = 'https://grup7dawcendrassos.cat/machinedetail/' . $machineId; // Replace with actual machine details URL

            // Set up the options for generating the QR code
            $options = new QROptions([
                'version'    => 7, // QR code version (size)
                'outputType' => QRCode::OUTPUT_MARKUP_SVG, // Output type as SVG
                'eccLevel'   => QRCode::ECC_L, // Error correction level (low)
                'svgViewBox' => true, // Enable viewBox for SVG
                'drawCircularModules' => true, // Draw circular modules in the QR code
                'circleRadius' => 0.5, // Radius for the circular modules
                'connectPaths' => true, // Connect paths in the QR code
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

            // Instantiate the QRCode object with the defined options
            $qrcode = new QRCode($options);
            // Generate the QR code as an SVG
            $svg = $qrcode->render($data);

            // Encode the SVG in base64 (not used in this code)
            $base64Svg = base64_encode($svg);

            // Assign the SVG as the data URI
            $dataUri = $svg;

            // Display the generated QR code as an image in the HTML
            echo '<img src="' . $dataUri . '" alt="QR Code" style="transform: scale(0.4); transform-origin: top center; display: block; margin: 0 auto; max-height: 100vh;"/>';
            exit; // End the script after displaying the image

        } else {
            // If no machine ID is provided, show an error
            $response->set('error', "Machine ID not provided.");
            $response->setTemplate('error.php'); // Render an error page
            return $response;
        }
    }
}
?>
