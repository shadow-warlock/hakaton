<?php
require_once __DIR__ . "/../libs/lib/swift_required.php";

spl_autoload_register(function ($class) {
    $url = str_replace('\\', '/', $class);
    $path = __DIR__ . '/' . $url . '.php';
    if (file_exists($path))
        require $path;
    else{
        $load_deps = [
            'Egulias\\EmailValidator\\' => __DIR__ . '/../libs/EmailValidator/',
            'Doctrine\\Common\\Lexer\\' => __DIR__ . '/../libs/Doctrine/Common/Lexer/',
        ];

        foreach ($load_deps as $prefix => $base_dir) {
            // does the class use the namespace prefix?
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) == 0) {

                // get the relative class name
                $relative_class = substr($class, $len);

                // replace the namespace prefix with the base directory, replace namespace
                // separators with directory separators in the relative class name, append
                // with .php
                $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

                // if the file exists, require it
                if (file_exists($file)) {
                    require $file;
                }
            }
        }

    }
//        echo "Class cannot load " . $class . " on path " . $path;
}, true);

