<?php

$stepName = $_GET['step'];

if (isset($_SESSION['tableProperties'])) {
	unset($_SESSION['tableProperties']);
	echo "Exiting Wizard Step {$stepName}.";
}

?>
