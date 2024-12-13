<?php
/*
 *	login.php - This page is dedicated to the login process.  It launches
 *			the login form to collect the user's credentials.  The result
 *			is the user's authorization object (JSON) is loaded into the
 *			session, and made available to the client.
 */

include_once ("includes.php");
include_once ("auth.class.php");

// Capture the referring URL from the session.
// Note: It must be the $_SESSION--not the $_SERVER variable. 
#$referer = $_SERVER['HTTP_REFERER'];
$referringURL = $_SESSION['HTTP_REFERER'];

$page = new appPage("login", "loginPage", "Sign in", "Sign in to use this application.");

// Add an empty menu just for the logo and link back home.
$menu = new menu("menu");
$page->addChild($menu);

// Add a title and description.
$header = new webObj("login", "loginHeader", "Sign in");
$header->template = "heading.html";

// 	It turns out that getting the referring URL into Javascript is a bit
//	tricky.  I need a simple way to render the URL to the HTML generated
//	for the login page.  So, I'm tucking it into the description.
$header->description = <<<"DESCRIPTION"
<p>Many PubAssist applications require you to sign in before you are authorized to use 
them.  Please note that this is specific to PubAssist.</p>
<h3>You will need PubAssist credentials--even if you are already logged into Wordpress</h3>
<script>var referringURL = "{$referringURL}"</script>

DESCRIPTION;
$page->addChild($header);

// Add the login form.
$form = new loginForm();
$page->addChild($form);

// Ready to render...
echo $page->render();
 
?>



