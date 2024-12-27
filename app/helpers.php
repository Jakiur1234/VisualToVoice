<?php

if( ! function_exists('dd') ) {
    function dd($data) {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die();
    }
}

if( ! function_exists('view') ) {
    function view($view, $data = []) {
        extract($data);
        $view = str_replace('.', '/', $view);
        require_once __DIR__ . "/../resources/views/{$view}.php";
    }
}
