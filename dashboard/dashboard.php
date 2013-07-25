<?php
session_start();
include("connect2db.php");
if($_POST)
{
$_SESSION['name'] = $_POST['name'];
$_SESSION['dj'] = $_POST['dj'];
$_SESSION['show'] = $_POST['show'];
$_SESSION['showtime'] = $_POST['showday'] . ", " . $_POST['showtime'];
$_SESSION['email'] = $_POST['email'];
}
include("header.php");

if(isset($_SESSION['name']))
{
echo
'<h3>Welcome to the DJ Dash v1.0!</h3>
ohmygodit\'ssoawesomeinhere<br/>
<br/>You are logged in as: ' . str_replace("\'","'",$_SESSION['name']) . '<br/><br/><form method="POST" action="logout.php"><input type="submit" value="Logout" />';
}
else
{
echo
'<h3>Please Log In:</h3>
<form method="POST" action="dashboard.php">
<table align="center">
<tr><td>Name(s):</td><td><input type="text" name="name"></td></tr>
<tr><td>Email(s):</td><td><input type="text" name="email"></td></tr>
<tr><td>DJ Name(s):</td><td><input type="text" name="dj"></td></tr>
<tr><td>Show Name:</td><td><input type="text" name="show"></td></tr>
<tr><td>Show Start Time and Day:</td><td><select name="showtime">';

for ($i=0;$i<24;$i++)
{
	echo '<option value="'; if($i<10){echo "0";} echo $i . ':00">'; if($i<10){echo "0";} echo $i .  ':00</option>';
}

echo '</select><select name="showday"><option value="Sunday">Sunday</option><option value="Monday">Monday</option><option value="Tuesday">Tuesday</option><option value="Wednesday">Wednesday</option><option value="Thursday">Thursday</option><option value="Friday">Friday</option><option value="Saturday">Saturday</option></select></td></tr></table><input type="hidden" name="inout" value="in"/><input type="submit" style="width:200px;" value="login"/></form>';
}

include("footer.php");

?>
