<?php
include_once ("includes.php");
include_once ("testPage.class.php");

$page = new testPage("testPage", "testPage", "Test Login Form");
$context = new context(	"auth", 
						"auth_no");
$page->context = $context;
$form = new loginForm();
$page->addForm($form);

// Add a button to launch the modal form.
$button = new webObj("loginButton", "loginForm", "Login", "Click to launch the login form.");
$button->template = "launchModalButton.html";
$page->addChild($button);

$html = $page->render();
echo $html;
?>
