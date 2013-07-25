<?php
session_start();
if(!isset($_SESSION['name']))
{
header('location: dashboard.php');
}

include("connect2db.php");
include("header.php");


set_include_path(get_include_path() . PATH_SEPARATOR . "$_SERVER[DOCUMENT_ROOT]/ZendGdata-1.10.7/library");
 
include_once("Google_Spreadsheet.php");
 
$u = "kure.spin.list";
$p = "carleatsballs";
 
$ss = new Google_Spreadsheet($u,$p);
$ss->useSpreadsheet("Transmighty Log");
$ss->useWorksheet("Log");

echo '<h3>Transmighty Log v1.0</h3>';

if($_POST['inout']=="in")
{
$_SESSION['djin']=1;
$row = array 
				(
				"Name" => str_replace("\'","'",$_SESSION['name'])
				, "DJ Name" => str_replace("\'","'",$_SESSION['dj'])
				, "Show Name" => str_replace("\'","'",$_SESSION['show'])
				, "Time In" => date('n/j/y H:i',time())
				);

		if ($ss->addRow($row)) echo str_replace("\'","'","Thank you, <strong>" . $_SESSION['name'] . "</strong>, you have been signed into the Transmighty Log v1.0!<br/>");
		else echo "I'm sorry, something went wrong.  It's not you, it's me. . . I just can't do this anymore<br/>";
}
elseif($_POST['inout']=="out")
{

$row = array 
				(
				"Name" => str_replace("\'","'",$_SESSION['name'])
				, "DJ Name" => str_replace("\'","'",$_SESSION['dj'])
				, "Show Name" => str_replace("\'","'",$_SESSION['show'])
				, "Time Out" => date('n/j/y H:i',time())
				);

		if ($ss->addRow($row)) echo str_replace("\'","'","Thank you, <strong>" . $_SESSION['name'] . "</strong>, you have been signed out of the Transmighty Log v1.0!<br/>");
		else echo "I'm sorry, something went wrong.  It's not you, it's me. . . I just can't do this anymore<br/>";


$_SESSION['djin']=0;
}



if($_SESSION['djin']==1)
{
echo
'<form action="login.php" method="post">
<input type="hidden" name="inout" value="out"/>
<input type="submit" value="Sign Out"/>

</form>';
}
else
{
echo
'<form action="login.php" method="post">
<input type="hidden" name="inout" value="in"/>
<input type="submit" value="Sign In"/>

</form>';
}

include("footer.php");
?>