<?php
function incidents($request, $response, $container){
    $response->setTemplate('incidents.php');
    return $response;
}