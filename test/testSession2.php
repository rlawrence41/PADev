<?php session_start();?>
<!DOCTYPE html>
<html>
<body>
<h1>Testing the Session Variable</h1>
<p>
<?php
// Echo session variables that were set on previous page
echo "Favorite color is " . $_SESSION["favcolor"] . ".<br>";
echo "Favorite animal is " . $_SESSION["favanimal"] . ".";
?>
</p>
<h2>What else is in $_SESSION?</h2>

<?php 
echo '<table cellpadding="10">' ; 
foreach($_SESSION as $varName => $value) {
	ob_start();
	var_dump($value);
	$varDump = ob_get_clean();
	echo "<tr><td>\$_SESSION['{$varName}']</td><td>{$varDump}</td></tr>" ; 
} 
echo '</table>' ;
?>

<p><a href="/">Back to the Test menu</a></p>

</body>
</html>