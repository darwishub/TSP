
<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'akun';
$koneksi    = mysql_connect($host,$user,$pass,$db);
     
    if(!$koneksi){
        die("Cannot connect to database." . mysql_connect_error());
    }
     
    mysql_select_db($db);
 
?>