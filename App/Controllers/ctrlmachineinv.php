<?php
function ctrlMachineInv($request, $response, $container){
    $response->setTemplate('machineinv.php');
    return $response;
}