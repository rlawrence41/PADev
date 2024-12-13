<?php
/*
	The following commands to access the Apache error log don't seem to work.
	Changing the protection on the log files had no effect.  I'm pretty sure 
	that this behavior is because Apache allows access only to the
	directories specified in the web site configuration.  
 */


/* $output = `tail -n 50 /var/log/apache2/error.log`;
echo "<pre>$output</pre>"; */


/*   exec('tail -n 50 /var/log/apache2/error.log', $error_logs);
  foreach($error_logs as $error_log) {echo "<br />".$error_log;}
 */

// Can't even list the log directory contents...
$output = `ls -l /var/log/apache2/`;
echo "<pre>$output</pre>";
