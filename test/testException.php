<?php

function exception_error_handler($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}

# throw new Exception("Missing POST Data");

function inverse($x) {
    if (!$x) {
        throw new ErrorException('Division by zero.');
    }
    return 1/$x;
}


set_error_handler("exception_error_handler");

$eol = "<br/>\n";

try {
    echo inverse(5) . $eol;
    echo inverse(0) . $eol;
} catch (Exception $e) {
    echo '<b>Caught exception: ', $e->getMessage(), 
	'  Severity: ', $e->getSeverity(), 
	'  File: ', $e->getFile(), 
	'  Line: ', $e->getLine(), 
	"</b>", $eol;
}

// Continue execution
echo "Hello World\n";

?>