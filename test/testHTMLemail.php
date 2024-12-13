<html>
   
   <head>
      <title>Sending HTML email using PHP</title>
   </head>
   
   <body>
      
      <?php
         $to = "ron@jerichoroadcrew.com";
         $subject = "This is an HTML email...";
         
         $message = "<b>This is an HTML message.</b>\n";
         $message .= "<h1>This is the headline.</h1>\n";
		 $message .= "<p>This is just a normal paragraph.</p>";
         
         $header = "From:paweb@pubassist.com \r\n";
		 $header .= "Reply-To: rlawrence@pubassist.com\r\n";  // Add a Reply-To header for additional clarity
		 $header .= "Return-Path: rlawrence@pubassist.com\r\n";
         $header .= "CC:rlawrence41@comcast.net \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         
		// Set the sender address using the -f option
		$additional_headers = "-f paweb@pubassist.com";

#         $retval = mail ($to,$subject,$message,$header);

		// Pass the additional headers to the mail() function
		$retval = mail($to, $subject, $message, $header, $additional_headers);
         
         if($retval) {
            echo "Message sent successfully...";
         }else {
            echo "Message could not be sent...";
         }
      ?>
      
   </body>
</html>