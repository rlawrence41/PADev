<?php include_once("includes.php"); ?>

<!DOCTYPE html>
<html>
<header>
<style>
  table {table-layout: fixed; width: 100%;}
  td {
	word-wrap: break-word;
	border-bottom-style: solid;
    border-bottom-width: 1pt;
	border-bottom-color: lightgray;
  }
</style>
</header>
<body>

<h1>Testing the PubAssist Configuration</h1>

<table>
<tr><th style="width:25%;">Property</th><th style="width:75%;">Setting</th></tr>
<tr>
<td><h3>Include Path:</h3></td> 
<td><?php echo $GLOBALS['includePath'] ; ?></td>
</tr>
<tr>
<td><h3>Configuration File: </h3></td>
<td><?php echo $GLOBALS['config'] ; ?></td>
</tr>

<tr>
<td><h3>SSL Certificate Path: </h3></td>
<td><?php echo $GLOBALS['sslCertPath'] ; ?></td>
</tr>

<tr>
<td><h3>REST API Path or URL: </h3></td>
<td><?php echo $GLOBALS['RESTroot'] ; ?></td>
</tr>

<tr>
<td><h3>Data Source Name [DSN]: </h3></td>
<td><?php echo $GLOBALS['DSN'] ; ?></td>
</tr>
</table>

<p><a href="/">Back to the Test menu</a></p>
</body>
</html>