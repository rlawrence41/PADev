<?php
/*
 *	whoAreYou.php - This page is dedicated to collecting the user's username,
 *			email, and/or password in order to begin the password reset
 *			process.  This page is launched when the user has indicated they
 *			have lost their password.
 */

include_once ("includes.php");
include_once ("auth.class.php");

// Capture the referring URL from the session.
// Note: It must be the $_SESSION--not the $_SERVER variable. 
#$referer = $_SERVER['HTTP_REFERER'];
$referringURL = $_SESSION['HTTP_REFERER'];

$page = new appPage("whoAreYou", "whoAreYouPage", "Sign in", "Sign in to use this application.");

// Add an empty menu just for the logo and link back home.
$menu = new menu("menu");
$page->addChild($menu);

// Add a title and description.
$header = new webObj("whoAreYou", "whoAreYouHeader", "Who Are You?");
$header->template = "heading.html";

$header->description = <<<"DESCRIPTION"
<p>Give us your username, email and/or your cell phone so we can locate you 
in our system.</p>
DESCRIPTION;
$page->addChild($header);

// Add the whoAreYou form.
$form = new whoAreYouForm();

// Default the "notifyMail" button to checked.
$form->children["notifyMail"]->value = true;

$page->addChild($form);

// Ready to render...
echo $page->render();

?>



