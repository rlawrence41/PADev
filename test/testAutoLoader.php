<?php

// Define a function to autoload classes
function myAutoloader($resource) {
 
    // Convert namespace separator '\' to directory separator '/'
#    $resource = str_replace('\\', '/', $resource);
    // Define the path to the class file based on the class name
	$classFile = __DIR__ . "/demo/{$resource}/ui/classes/{$resource}.class.php";
    // Check if the class file exists and require it if found
echo "Require: " . $classFile ;
    if (file_exists($classFile)) {
        require_once $classFile;
    }

	// Now, define the path to the derived class.
	$classFile = __DIR__ . "/demo/{$resource}/{$resource}Admin.php";
    // Check if the class file exists and require it if found
echo "Require derived class: " . $classFile ;
    if (file_exists($classFile)) {
        require_once $classFile;
    }
}

include_once("demo/includes.php");

// Register the autoloader function
#spl_autoload_register('myAutoloader');

// Now you can use your classes without manually including them
#use contact;
myAutoLoader("contact");

#$myObject = new contactPage();
#echo var_dump($myObject) ;

?>
