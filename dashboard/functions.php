<?php
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

//Sorts Song Data Array by the chosen selector.
//  Selectors available are: artist, album, count (plays)
//  $ar should be an array arranged [0] => Artist Name [1] => Album Name [2] => Play Count
function sortmultiarray($ar,$sortby)
{
switch ($sortby)
	{
	//When sorting by artist
	case "artist":
        //For each entry in the array
        for ($q=0;$q<count($ar);$q++)  
        //sort the array
		array_multisort($ar_lo, SORT_ASC, SORT_STRING, $ar);
		break;
	case "album":
	   // For each entry in the array
		for ($q=0;$q<count($ar);$q++)
		{
		  // Switch the artist name with album name 
		  $temp = $ar[$q][0];
		  $ar[$q][0] = $ar[$q][1];
		  $ar[$q][1] = $temp;
		}
		// Sort by album name
		array_multisort($ar_lo, SORT_ASC, SORT_STRING, $ar);
		// For each entry in the array
		for ($q=0;$q<count($ar);$q++)
		{
		  // Switch artist name and album name back
		  $temp = $ar[$q][1];
		  $ar[$q][1] = $ar[$q][0];
		  $ar[$q][0] = $temp;
		}
		break;
	case "song":
	   // For each entry in the array
		for ($q=0;$q<count($ar);$q++)
		{
		  // Switch the artist name with track name 
		  $temp = $ar[$q][0];
		  $ar[$q][0] = $ar[$q][3];
		  $ar[$q][3] = $temp;
		}
		// Sort by album name
		array_multisort($ar_lo, SORT_ASC, SORT_STRING, $ar);
		// For each entry in the array
		for ($q=0;$q<count($ar);$q++)
		{
		  // Switch artist name and album name back
		  $temp = $ar[$q][3];
		  $ar[$q][3] = $ar[$q][0];
		  $ar[$q][0] = $temp;
		}
		break;
	case "count":
	   // For each entry in the array
		for ($q=0;$q<count($ar);$q++)
		{
		  // Switch artist name and count
		  $temp = $ar[$q][0];
		  $ar[$q][0] = $ar[$q][2];
		  $ar[$q][2] = $temp;
        }
        array_multisort($ar, SORT_DESC, $ar);
		for ($q=0;$q<count($ar);$q++)
		{
		  $temp = $ar[$q][2];
		  $ar[$q][2] = $ar[$q][0];
		  $ar[$q][0] = $temp;
		}
		break;
	}
return $ar;
}
?>