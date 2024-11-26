<?php
namespace App\Views;

class View {
    private $path;

    public function __construct($path) {
        $this->path = $path;
    }

    public function render($template, $data = []) {
        extract($data);
        ob_start();
        include($this->path . $template);
        $content = ob_get_clean();
        return $content;
    }
} 