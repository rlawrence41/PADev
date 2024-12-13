<?php
session_start();
$_SESSION["favcolor"]="blue";
$_SESSION["favanimal"]="dog";

?>
<!DOCTYPE html>
<html>
<body>
<h1>Test the Session Variable</h1>
<p>
Go to the next page: <a href="testSession2.php">testSession2.php</a>
</p>
</body>
</html>