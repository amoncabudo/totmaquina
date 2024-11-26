<?php
function ctrlIndex($request, $response, $container){
    $response->setTemplate('index.php');
    return $response;
} 