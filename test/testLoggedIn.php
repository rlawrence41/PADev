<?php

function redirect($url, $permanent = false)
{
    header('Location: ' . $url, true, $permanent ? 301 : 302);
    exit();
}

session_start();
if (empty($_SESSION['auth'])){

#	$_SERVER['HTTP_REFERER'] = $_SERVER['REQUEST_URI'];
	$_SESSION['HTTP_REFERER'] = $_SERVER['REQUEST_URI'];

	
	redirect('/login.php', false);
#	redirect('testPHP_SERVER.html');
#	redirect('testSession2.php');
	
}
?>
<!DOCTYPE html>
<html>
<body>
<h1>Test whether a user is logged in</h1>
<p>
You won't see this page until you have successfully logged in.
</p>

<p><a href="/">Back to the Test menu</a></p>

</body>
</html>