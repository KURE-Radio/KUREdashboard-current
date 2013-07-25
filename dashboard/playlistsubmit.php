<?php
session_start();
if(!isset($_SESSION['name']))
{
header('location: dashboard.php');
}

include("connect2db.php");
include("header.php");
/**require ("lastfmapi/lastfmapi.php");
*
*
*$lasttoken = "SELECT token FROM lasttoken";
*$fetchtoken = mysql_fetch_row(mysql_query($lasttoken));
*
*$vars = array(
*	'apiKey' => '2b7ac8db12bfa0039bf30d6fcca9a183',
*	'secret' => '6328cae78af992551994d1c495ff1afb',
*	'token' => $fetchtoken[0]
*);
*
*
*$auth = new lastfmApiAuth('getsession', $vars);
*
*echo $auth -> sessionKey;
*/

function normstring($str,$the)
{
if ($str == "")	{
return $str;
}
elseif ($str == " ")	{
$str = "";
return $str;
}
else
{
$str = explode(" ",$str);

//Remove leading and trailing spaces
while ($str[0] == '' || $str[0] == ' ' || $str[(count($str)-1)] == '' || $str[(count($str)-1)] == ' ')
{
if ($str[0] == '' || $str[0] == ' ')
	array_shift($str);
if ($str[(count($str)-1)] == '' || $str[(count($str)-1)] == ' ')
	array_pop($str);
}	
if($the == 1)
{
switch ($str[0])
{
	case "the":
		array_shift($str);
		break;
	case "The":
		array_shift($str);
		break;
}
}
$str = implode(" ",$str);
$replacees = array("'","&",",","\'",".","À","à","È","è","Ì","ì","Ò","ò","Ù","ù","Á","É","Í","Ó","Ú","Ý","á","é","í","ó","ú","ý","á","é","í","ó","ú","ý","Â","Ê","Î","Ô","Û","â","ê","î","ô","û","Ã","Ñ","Õ","ã","ñ","õ","Ä","Ë","Ï","Ö","Ü","Ÿ","ä","ë","ï","ö","ü","ÿ");
$replacers = array("","and","","","","a","a","e","e","i","i","o","o","u","u","a","e","i","o","u","y","a","e","i","o","u","y","a","e","i","o","u","y","a","e","i","o","u","a","e","i","o","u","a","n","o","a","n","o","a","e","i","o","u","y","a","e","i","o","u","y");
$str = str_replace($replacees, $replacers, $str);
$str = stripslashes($str);
$str = strtoupper($str);
return $str;
}
}



require("Scrobbler.php");
$scrobbler = new md_Scrobbler('kure885', 'carleatsballs', '2b7ac8db12bfa0039bf30d6fcca9a183', '6328cae78af992551994d1c495ff1afb', '581b51dd77d7d5c08931dc4f425111cf', 'tst', '1.0');

$showname = normstring($_SESSION['show'],0);
$djname = normstring($_SESSION['dj'],0);
$showtime = normstring($_SESSION['showtime'],0);

$success = " was successfully GigaSpun using Giga Spin List v1.1!<br/>";
 
echo '<div id="left1">
<h3>Giga Spin List v1.1</h3>';

for ($a=1;$a<=40;$a++)
{
	if ($_POST['song' . $a] != "")
	{
		if ($_POST['artist' . $a] == "Lady Gaga")
		{
			echo "Lady Gaga?!  <strong>Fucking REALLY?</strong>  Not on my station, you don't!!<br/><br/>";
		}
		else
		{
			$track = normstring($_POST['song' . $a],0);
			$artist = normstring($_POST['artist' . $a],1);
			$album = normstring($_POST['album' . $a],0);
			$hash = md5($album . " " . $artist);
			$addspin = "INSERT INTO gigaspinlist VALUES ('$track', '$artist', '$album', '$showname', '$djname', '$showtime', CURRENT_TIMESTAMP, '$hash')";
			if(mysql_query($addspin))
			{
			echo "<strong>" . $track . "</strong>" . " by " . "<strong>" . $artist . "</strong>" ." from " . "<strong>" . $album . "</strong>" .$success . " <br/>";
			}
			else
			{
			echo "Error, unable to store data<br/>";
			}
			$scrobtime=time() - $a * 180;
			$scrobbler->add(str_replace("\'","'",$_POST['artist' . $a]), str_replace("\'","'",$_POST['song' . $a]), str_replace("\'","'",$_POST['album' . $a]),"300",$scrobtime);

		}
	}
}



if ($_POST['type'] != "none")
{
		switch($_POST['type'])
		{
			case "t":
				$delim = "\t";
				break;
			case ",":
				$delim = ",";
				break;
			case ";":
				$delim = ";";
				break;
			case "-":
				$delim = "-";
				break;
		}

		switch($_POST['format'])
		{
			case "tr_ar_al":
				$tr = 0;
				$ar = 1;
				$al = 2;
				break;
			case "tr_al_ar":
				$tr = 0;
				$ar = 2;
				$al = 1;
				break;
			case "ar_tr_al":
				$tr = 1;
				$ar = 0;
				$al = 2;
				break;
			case "ar_al_tr":
				$tr = 2;
				$ar = 0;
				$al = 1;
				break;
			case "al_ar_tr":
				$tr = 2;
				$ar = 1;
				$al = 0;
				break;
			case "al_tr_ar":
				$tr = 1;
				$ar = 2;
				$al = 0;
				break;
		}

	$massdata = explode("\n", $_POST['massplaylist']);
	for ($a=0;$a<=count($massdata)-1;$a++)
	{
		$data = explode($delim, $massdata[$a]);
		if($data[0] != "" && $data[1] != "" && $data[2] != "")
		{
			$track = normstring($data[$tr],0);
			$artist = normstring($data[$ar],1);
			$album = normstring($data[$al],0);
			$hash = md5($album . " " . $artist);
			$addspin = "INSERT INTO gigaspinlist VALUES ('$track', '$artist', '$album', '$showname', '$djname', '$showtime', CURRENT_TIMESTAMP, '$hash')";
			if(mysql_query($addspin))
			{
			echo "<strong>" . $track . "</strong>" . " by " . "<strong>" . $artist . "</strong>" ." from " . "<strong>" . $album . "</strong>" .$success . " <br/>";
			}
			else
			{
			echo "Error, unable to store data<br/>";
			}

			$scrobtime=time() - (count($massdata) - $a) * 180;
			$scrobbler->add(str_replace("\'","'",$data[$ar]), str_replace("\'","'",$data[$tr]), str_replace("\'","'",$data[$al]),"300",$scrobtime);
		}
	}
}
			$scrobbler->submit();

echo "Thanks again, your submission is complete, it's now safe to close this browser window, or you can <a href='playlist.php'>go back to the submission form!</a></div><br/>";

include("footer.php");
?>


