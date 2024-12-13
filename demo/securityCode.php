<?php
/*
 *	securityCode.php - This page is dedicated to the securityCode process.  It launches
 *			the securityCode form to collect the user's credentials.  The result
 *			is the user's authorization object (JSON) is loaded into the
 *			session, and made available to the client.
 */

include_once ("includes.php");
include_once ("auth.class.php");

$page = new appPage("securityCode", "securityCodePage", "Security Code", "Enter the security code we sent you.");

// Add an empty menu just for the logo and link back home.
$menu = new menu("menu");
$page->addChild($menu);

// Add a title and description.
$header = new webObj("securityCode", "securityCodeHeader", "Security Code");
$header->template = "heading.html";

$header->description = <<<"DESCRIPTION"
<p>Enter the security code we sent you.</p>
DESCRIPTION;
$page->addChild($header);

// Add the securityCode form.
$form = new securityCodeForm();

// Add the current authorization values to the form.
$form->children["id"]->value = $_SESSION['auth']['id'];
$form->children["user_id"]->value = $_SESSION['auth']['user_id'];

$page->addChild($form);

// Ready to render...
echo $page->render();
 
?>



