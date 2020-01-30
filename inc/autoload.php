<?php
// Include all files here so only 1 include in play.php
include "debug_functions.php";

// Autoload our classes
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});
