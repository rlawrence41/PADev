<?php
include ("includes.php");
session_start();
unset($_SESSION['auth']);
echo "User has been logged out of REST.";