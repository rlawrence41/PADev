
<?php
$phoneStr = "(802) 899-1139";
$strippedPhone = preg_replace("#[[:punct:] ]#", "", $phoneStr);
$result = preg_match("/\d/g", $phoneStr, $matches);
$strippedPhone2 = $matches[0];
print_r($matches);
?>

<h1>Strip Punctuation from Phone Number</h1>

<p>Original phone number: <?php echo $phoneStr; ?>.
<p>Phone with punctuation removed: <?php echo $strippedPhone; ?>.</p>
<p>Phone with punctuation removed (2): <?php echo $strippedPhone2; ?>.</p>