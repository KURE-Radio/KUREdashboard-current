<?php
session_start();

include("connect2db.php");

if($_POST)
{
$kureid = $_POST['kureid'];
$kurepass = md5($_POST['kurepass']);
$query = "SELECT name, djname, id, email FROM djdata WHERE kureid ='$kureid' AND kurepass = '$kurepass'";
if($fetchone = mysql_fetch_row(mysql_query($query)))
{
$invalid = 0;
}
else
{
$invalid = 1;
}
$_SESSION['name'] = $fetchone[0];
$_SESSION['dj'] = $fetchone[1];
$_SESSION['id'] = $fetchone[2];
$_SESSION['email'] = $fetchone[3];
}

include("header.php");

if(isset($_SESSION['name']))
{
echo
'<h3>Welcome to the DJ Dash v1.3!</h3>
ohmygodit\'ssoawesomeinhere<br/>
<br/>You are logged in as: ' . str_replace("\'","'",$_SESSION['name']) . '<br/><br/><form method="POST" action="logout.php"><input type="submit" value="Logout" />';
}
else
{
echo
'<div>';
if ($invalid)
{
echo '<font color="red">Invalid Username or Password!</font>';
}
echo
'<h3>Please Log In:</h3>
<form method="POST" action="index.php">
<table align="center">
<tr><td>KURE-ID:</td><td><input type="text" name="kureid"></td></tr>
<tr><td>Password:</td><td><input type="password" name="kurepass"></td></tr>
<tr><td colspan="2"><input type="submit" style="width:200px;" value="login"/></td></tr></table></form></div>';
}

include("footer.php");

?>