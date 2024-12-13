<?php

include_once ("includes.php");

$myWebComponent = new webObj("myComponent", "myCompId", "Web Component", "This is a test." );
$mySubComponent = new webObj("mySubComponent", "mySubCompId", "Sub Component", "This test is for a sub component." );
print_r($myWebComponent);
var_dump($mySubComponent);
$myWebComponent->addChild($mySubComponent);
print_r($myWebComponent);
