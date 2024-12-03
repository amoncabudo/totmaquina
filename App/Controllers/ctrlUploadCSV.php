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
                    $name = $data[0];
                    $model = $data[1];
                    $manufacturer = $data[2];
                    $location = $data[3];
                    $installation_date = $data[4];
                    $serial_number = $data[5];
                    $photo = $data[6];
                    $coordinates = $data[7];
                    // Assuming CSV columns: name, model, manufacturer, location, installation_date, serial_number, photo
                        $machineModel->insertMachine($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7]);
                }
                fclose($handle);
            }
        }

        $response->setHeader("Location: /machineinv");
        return $response;
    }
} 