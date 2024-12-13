<html>
<body>
<h1>Test REST Includes</h1>


<?php

include ('common/rest/includes.php');
echo $GLOBALS['includePath'];

$included_files = get_included_files();
print_r($included_files);

?>

<p><a href="/">Back to the Test menu</a></p>

</body>
</html>