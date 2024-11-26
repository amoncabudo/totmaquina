<?php
function maintenance($request, $response, $container){
    $response->setTemplate('maintenance.php');
    return $response;
}