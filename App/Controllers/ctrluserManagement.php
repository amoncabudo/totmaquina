<?php

function ctrlUserManagement($request, $response, $container){
    $response->setTemplate('userManagement.php');
    return $response;
}