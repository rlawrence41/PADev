<html>
<body>
<h1>PHP _SERVER Variables</h1>
<table cellpadding="10">

<?php
 
foreach($_SERVER as $paramName => $varServer) {
    if (isset($_SERVER[$paramName])) { 
        ob_start();
        var_dump($varServer);
        $varDump = ob_get_clean();
#        echo "<tr><td>\$_SERVER['{$paramName}']</td><td>{$varDump}</td></tr>" ; 

	echo <<<tableRow
<tr>
	<td style="vertical-align:top">\$_SERVER['{$paramName}']</td>
	<td style="vertical-align:top">
	  <pre>
{$varDump}
	  </pre>
	</td>
</tr>
tableRow ; 

    } 
    else echo '<tr><td>'.$paramName.'</td><td>-</td></tr>' ; 
} 
?>
</table>
<p><a href="/">Back to the Test menu</a></p>

</body>
</html>