<?php
$eol = "<br/>\n";
#$dsn = 'Driver={MariaDB Unicode};SERVER=localhost;DATABASE=pubassistDemo';
$dsn = 'pubassistDemo';
$user='www-data';
$password='PAWebUser';

#$conn = odbc_connect($dsn, $user, $password);
$conn = odbc_connect('dsn', '', '');

$dsn_info = odbc_data_source($conn, SQL_FETCH_FIRST);
while ($dsn_info) {
    print_r($dsn_info);
	echo $eol;
    $dsn_info = odbc_data_source($conn, SQL_FETCH_NEXT);
}
?>