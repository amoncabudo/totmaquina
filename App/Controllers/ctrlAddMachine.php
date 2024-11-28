<?php

function ctrlAddMachine($request, $response, $container){
    $response->setTemplate('addmachine.php');
    return $response; 
} 