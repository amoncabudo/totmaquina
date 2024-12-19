<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class UploadCSVController
{
    /**
     * Handles the upload and processing of a CSV file.
     */
    public function uploadCSV($request, $response, $container)
    {
        // Check if a file has been uploaded and if there are no errors
        if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
            // Get the temporary file path of the uploaded CSV
            $file = $_FILES['csv_file']['tmp_name'];

            // Get the 'Machine' model from the container
            $machineModel = $container->get('Machine');

            // Open the CSV file for reading
            if (($handle = fopen($file, "r")) !== FALSE) {
                // Read the first row (header row) and ignore it
                fgetcsv($handle, 1000, ",");

                // Read the CSV file line by line
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    // Extract data from each CSV row
                    $name = $data[0];
                    $model = $data[1];
                    $manufacturer = $data[2];
                    $location = $data[3];
                    $installation_date = $data[4];
                    $serial_number = $data[5];
                    $photo = $data[6];
                    $coordinates = $data[7];

                    // Insert each machine's data into the database using the 'insertMachine' method
                    // Assuming the CSV columns: name, model, manufacturer, location, installation_date, serial_number, photo, coordinates
                    $machineModel->insertMachine($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7]);
                }

                // Close the CSV file after reading
                fclose($handle);
            }
        }

        // Set the response header to redirect to the machine inventory page
        $response->setHeader("Location: /machineinv");

        // Return the response object
        return $response;
    }
}
