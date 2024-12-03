<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Data\QRMatrix;

if (isset($_GET['id'])) {
    $data = 'https://example.com/machine/' . $_GET['id']; // Replace with your actual URL
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
                <stop stop-color="#e2453c" offset="0"/>
                <stop stop-color="#e98e3b" offset="0.2"/>
                <stop stop-color="#e3e437" offset="0.4"/>
                <stop stop-color="#51b95f" offset="0.6"/>
                <stop stop-color="#1a7bcc" offset="0.8"/>
                <stop stop-color="#6f5ba7" offset="1"/>
            </linearGradient>
            <style><![CDATA[
                .dark{fill: url(#rainbow);}
                .light{fill: #eee;}
            ]]></style>
        ',
    ]);

    $qrcode = new QRCode($options);
    $svg = $qrcode->render($data);

    header('Content-Type: image/svg+xml');
    echo $svg;
}