<?php                                    // 1
require '../vendor/autoload.php';           // 2

$app = new \atk4\ui\App('My First App'); // 3
$app->initLayout('Centered');            // 4

$app->add('HelloWorld');                 // 5
