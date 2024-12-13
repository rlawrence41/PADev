<?php

// This procedure is used to test the menu class.  View the page source
//	to verify whether the appropriate HTML was generated.

include_once ("includes.php");
include_once ("menu.class.php");
include_once ("standardMenu.class.php");
include_once ("form.class.php");
include_once ("loginForm.class.php");

$page = new webObj("testMenu", "testMenu", "Test Menu");
$page->template = "pageBootstrap2.html";

$menu = new standardMenu();
$page->addChild($menu);

$form = new loginForm();
$page->addChild($form);

$html = $page->render();
echo $html;
?>
<p><a href="/">Back to the test menu</a></p>

