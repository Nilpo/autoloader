<?php

// These lines are for DEVELOPMENT only.  You should never display errors
// in a production environment.
error_reporting(E_ALL);
ini_set( 'display_errors','1');

include 'classes/Autoloader.php';

try {
    $a = new A;
    $b = new B;
    $c = new C;
}
catch (Exception $e) {
	echo $e->getMessage();
}

// EOF
