<?php
function history($request, $response, $container){
    $response->setTemplate('history.php');
    return $response;
}