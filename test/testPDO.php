
<html><body>
<h1>Testing ODBC</h1>
<p>This page connects to an ODBC DSN using PDO.  </p>

<p>&nbsp;</p>
<h2>

<?php 

try{
    $connection = new PDO ("odbc:pubassist");

    die(json_encode(array('outcome' => true)));
}
catch(PDOException $ex){
     die(json_encode(array('outcome' => false, 'message' => 'Unable to connect')));
}

?>

</h2>
</body></html>



