<?php
/*
 *	resetPassword.php - This page is dedicated to the resetPassword process.  It launches
 *			the resetPassword form to collect the user's new password.  The user must be
 *		 	logged in to be able to reset their password.  They will remain logged in 
 *			after the password has been changed.
 */

include_once ("includes.php");
include_once ("auth.class.php");

$page = new appPage("resetPassword", "resetPasswordPage", "Reset Password", "Enter your new password.");

// Add an empty menu just for the logo and link back home.
$menu = new menu("menu");
$page->addChild($menu);

// Add a title and description.
$header = new webObj("resetPassword", "resetPasswordHeader", "Reset Password");
$header->template = "heading.html";

$header->description = <<<"DESCRIPTION"
<p>Enter your new password.</p>
DESCRIPTION;
$page->addChild($header);

// Add the resetPassword form.
$form = new resetPasswordForm();

// Add the current authorization values to the form.
$form->children["id"]->value = $_SESSION['auth']['id'];
$form->children["user_id"]->value = $_SESSION['auth']['user_id'];

$page->addChild($form);

// Ready to render...
echo $page->render();
 
?>



