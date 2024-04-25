<?php
function my_plugin_autoloader($class_name) {
    // Define your plugin namespace and base directory
    $namespace = 'MSO';
    $base_dir = __DIR__ . '/';

    // Remove namespace prefix and directory separator
    $class_name = str_replace($namespace . '\\', '', $class_name);

    // Replace namespace separator with directory separator
    $class_file = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);

    // Load the class file if it exists
    $file = $base_dir . $class_file . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

// Register the autoloader
spl_autoload_register('my_plugin_autoloader');

