<?php

$to = "rlawrence41@comcast.net";
$subject = "Testing PHP email...";

$headers = "From: paweb@pubassist.com\r\n" .
			"Reply-To: rlawrence@pubassist.com\r\n" .  // Add a Reply-To header for additional clarity
			"Return-Path: rlawrence@pubassist.com\r\n" .
			"CC: ron@jerichoroadcrew.com";


// the message
$msg = "First line of text.\nSecond line of text.\n";
$msg .= "This is a long line of text to see what happens if no wrapping is employed.  It doesn't really matter what the text is.  This is just a test.  I am continually frustrated by email newsletters that don't wrap based on my email client's preview pane." ;

// use wordwrap() if lines are longer than 70 characters
#$msg = wordwrap($msg,70);

#mail($to,$subject,$msg,$headers);

// Set the sender address using the -f option
$additional_headers = "-f paweb@pubassist.com";

// Pass the additional headers to the mail() function
mail($to, $subject, $msg, $headers, $additional_headers);


?>
