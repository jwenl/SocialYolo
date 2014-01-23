<?php
spl_autoload_register(function($class) {
    if (!substr($class, 0, 6) === 'Social') {
        return;
    }

    $location = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (is_file($location)) {
        require_once($location);
    }
});