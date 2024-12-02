<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class UploadCSVController
{
    public function uploadCSV($request, $response, $container)
    {
        if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
            $file = $_FILES['csv_file']['tmp_name'];
            $machineModel = $container->get('Machine');

            if (($handle = fopen($file, "r")) !== FALSE) {
                // Assuming the first row is the header
                fgetcsv($handle, 1000, ",");

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    // Assuming CSV columns: name, model, manufacturer, location, installation_date, serial_number, photo
                    $machineModel->insertMachine($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);
                }
                fclose($handle);
            }
        }

        $response->setHeader("Location: /machineinv");
        return $response;
    }
} 