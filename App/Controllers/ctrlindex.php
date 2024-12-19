<?php
// Function to handle the index page request
function ctrlIndex($request, $response, $container) {
    // Set the template for the response to 'index.php'
    $response->setTemplate('index.php');
    
    // Return the response object
    return $response;
}
?>
