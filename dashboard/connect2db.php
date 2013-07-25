<?php
session_start();
$mysql_database="stuorg_kure";
$mysql_username="stuorg_kure";
$mysql_password="sniOmkisyovyud5";

$link = mysql_connect("db01.stuorg.iastate.edu",$mysql_username,$mysql_password) or die ("Unable to connect to SQL server");
mysql_select_db($mysql_database) or die ("Unable to select database");

?>