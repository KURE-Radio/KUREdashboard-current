<?php
session_start();
//if(!isset($_SESSION['name']))
//{
//header('location: dashboard.php');
//}

include("connect2db.php");
include("header.php");

//Retreve selections or set to defaults
if ($_GET["sort"] != null) 
    $sort = $_GET["sort"];
else 
    $sort = 'count';
if ($_GET["tally"] != null) 
    $tally = $_GET["tally"];
else 
    $tally = 'album';
if ($_GET["time"] != null) 
    $time = (int) $_GET["time"];
else 
    $time = 1;

switch ($time)
{
    case 1:
        $sql_date_string = " AND submittime >= SUBDATE(CURRENT_TIMESTAMP, INTERVAL 1 WEEK)";
        break;
    case 2:
        $sql_date_string = " AND submittime >= SUBDATE(CURRENT_TIMESTAMP, INTERVAL 2 WEEK)";
        break;
    case 3:
        $sql_date_string = " AND submittime >= SUBDATE(CURRENT_TIMESTAMP, INTERVAL 1 MONTH)";
        break;
    case 4:
        $sql_date_string = "";
        break;
}


echo '<div id="left1">
<h3>Giga Spin List v1.1</h3>';
echo '    <form action="spinlist.php" method="get">';

echo '    <p>Tally By: 
          <input type="radio" name="tally" value="artist"';if ($tally=='artist') echo 'Checked';echo ' /> Artist';
echo '    <input type="radio" name="tally" value="album"';if ($tally=='album') echo 'Checked';echo ' /> Album ';
echo '    <input type="radio" name="tally" value="song"';if ($tally=='song') echo 'Checked';echo ' /> Song</p>';

echo '    <p>Sort By: 
        <input type="radio" name="sort" value="artist"';if ($sort=='artist') echo 'Checked';echo ' /> Artist'; 
echo '    <input type="radio" name="sort" value="album"';if ($sort=='album') echo 'Checked';echo ' /> Album';
echo '    <input type="radio" name="sort" value="song"';if ($sort=='song') echo 'Checked';echo ' /> Song';
echo '    <input type="radio" name="sort" value="count"';if ($sort=='count') echo 'Checked';echo ' /> Tally</p>';

echo '    <p>Track Plays Since: ';
echo '    <SELECT name="time">';
echo '        <OPTION ';if ($time==1) echo 'SELECTED'; echo ' value="1"> 1 Week Ago';
echo '        <OPTION ';if ($time==2) echo 'SELECTED'; echo ' value="2">2 Weeks Ago';
echo '        <OPTION ';if ($time==3) echo 'SELECTED'; echo ' value="3">1 Month Ago';
echo '        <OPTION ';if ($time==4) echo 'SELECTED'; echo ' value="4">All Time';
echo '    </SELECT>';
echo '<input type="submit" value="Tabulate!"/></p>';
echo '</form>';
echo '<table>';



//Tally by album assuming no song data wanted
if (($sort != 'song') && ($tally == 'album'))
{
    // Determine the number of distinct artist and album entries
    $query = "SELECT DISTINCT artist, album FROM gigaspinlist WHERE 1 " . $sql_date_string;
    $max = mysql_num_rows(mysql_query($query));
    // For each combination
    for ($i=0; $i<$max; $i++)
        {
            // Query the database for each distinct album and artist entry
            $scanone = "SELECT DISTINCT artist, album FROM gigaspinlist WHERE 1" . $sql_date_string . " LIMIT $i,1";
            $fetchone = mysql_fetch_row(mysql_query($scanone));
            $spinny[$i][0] = normstring($fetchone[0],1);
            $spinny[$i][1] = normstring($fetchone[1],0);
        }
    foreach ($spinny as &$entry)
    {
                // count the number of distinct album and arist entries in the database for this entry
                $artist = strtoupper(str_replace("'","\'",$entry[0]));
                $album = strtoupper(str_replace("'","\'",$entry[1]));
                $counter = "SELECT * FROM gigaspinlist WHERE upper(artist) = '$artist' AND upper(album) = '$album' " . $sql_date_string;
                $count = mysql_num_rows(mysql_query($counter));
                // Record number of plays for the artist
                $entry[2] = $count;
    }	
}

//Tally by artist assuming no song data is wanted
if (($sort != 'song') && ($tally == 'artist'))
{
    // Determine the number of distinct artist and album entries
    $query = "SELECT DISTINCT artist FROM gigaspinlist WHERE 1 " . $sql_date_string;
    $max = mysql_num_rows(mysql_query($query));
    // For each combination
    for ($i=0; $i<$max; $i++)
        {
            // Query the database for each distinct album and artist entry
            $scanone = "SELECT DISTINCT artist FROM gigaspinlist WHERE 1 " . $sql_date_string. " LIMIT $i,1";
            $fetchone = mysql_fetch_row(mysql_query($scanone));
            $spinny[$i][0] = normstring($fetchone[0],1);
            $spinny[$i][1] = normstring($fetchone[1],0);
        }
    foreach ($spinny as &$entry)
    {
                // count the number of distinct arist entries in the database for this entry
                $artist = strtoupper(str_replace("'","\'",$entry[0]));
                $counter = "SELECT * FROM gigaspinlist WHERE upper(artist) = '$artist'" . $sql_date_string;
                $count = mysql_num_rows(mysql_query($counter));
                // Strip album information since it is not desired in this case
                $entry[1] = null;
                // Record number of plays for the artist
                $entry[2] = $count;
    }	
}

//Tally by artist assuming no song data is wanted
if (($sort == 'song') || ($tally == 'song'))
{
    // Determine the number of distinct track entries
    $query = "SELECT DISTINCT artist, album, track FROM gigaspinlist WHERE 1 " . $sql_date_string;
    $max = mysql_num_rows(mysql_query($query));
    // For each combination
    $i=0; $j=0;
    while ($i<$max)
        {
            // Query the database for each distinct track entry
            $scanone = "SELECT DISTINCT artist, album, track FROM gigaspinlist WHERE 1 " . $sql_date_string. " LIMIT $i,1";
            $fetchone = mysql_fetch_row(mysql_query($scanone));
            if ($fetchone[2] != 'A')
            {
                $spinny[$j][0] = normstring($fetchone[0],1);
                $spinny[$j][1] = normstring($fetchone[1],0);
                $spinny[$j][2] = null;
                $spinny[$j][3] = normstring($fetchone[2],0);
                $j++;
            }
            $i++;
        }
    $max = $j;
    if ($tally == 'song')
    {
        foreach ($spinny as &$entry)
        {
                // count the number of distinct track entries in the database for this entry
                $artist = strtoupper(str_replace("'","\'",$entry[0]));
                $album = strtoupper(str_replace("'","\'",$entry[1]));
                $track = strtoupper(str_replace("'","\'",$entry[3]));
                $counter = "SELECT * FROM gigaspinlist WHERE upper(artist) = '$artist' AND upper(album) = '$album' AND upper(track) = '$track'" . $sql_date_string;
                $count = mysql_num_rows(mysql_query($counter));
                // Record number of plays for the track
                $entry[2] = $count;
        }
    }
    if	($tally == 'artist')
    {
    foreach ($spinny as &$entry)
    {
                // count the number of distinct arist entries in the database for this entry
                $artist = strtoupper(str_replace("'","\'",$entry[0]));
                $counter = "SELECT * FROM gigaspinlist WHERE upper(artist) = '$artist'" . $sql_date_string;
                $count = mysql_num_rows(mysql_query($counter));
                // Record number of plays for the artist
                $entry[2] = $count;
    }	
    }
    if ($tally == 'album')
    {
    foreach ($spinny as &$entry)
    {
                // count the number of distinct album and arist entries in the database for this entry
                $artist = strtoupper(str_replace("'","\'",$entry[0]));
                $album = strtoupper(str_replace("'","\'",$entry[1]));
                $counter = "SELECT * FROM gigaspinlist WHERE upper(artist) = '$artist' AND upper(album) = '$album'" . $sql_date_string;
                $count = mysql_num_rows(mysql_query($counter));
                // Record number of plays for the artist
                $entry[2] = $count;
    }	

    }
}



$spinny = sortmultiarray($spinny,$sort);
if ($sort = 'count')
{
    for ($i=0; $i<$max; $i++)
    {
        $j = $i + 1;
        echo '<tr><td>' . $j . '</td><td>' . $spinny[$i][0] . '</td><td>' . $spinny[$i][1] . '</td><td>' . $spinny[$i][3] . '</td><td>' . $spinny[$i][2] . '</td></tr>';
    }
}
else
{
    for ($i=0; $i<$max; $i++) echo '<tr><td>' . $spinny[$i][0] . '</td><td>' . $spinny[$i][1] . '</td><td>' . $spinny[$i][3] . '</td><td>' . $spinny[$i][2] . '</td></tr>';
}

echo
'</table></div>';

include("footer.php");


//Removes "The" and "the" from the 
function normstring($str,$the)
{
    //Break the string into component words by spaces
    $str = explode(" ",$str);
    // If catching 'The's
    if($the == 1)
    {
        // If the first word is "The" in any capitalization form
        if (strtolower($str[0]) == strtolower("the"))
            //Remove the first word (The)
            array_shift($str);
    }
    //Colapse the array into a single string
    $str = implode(" ",$str);
    //Capitalize each word of the string.
    $str = ucwords($str);
    
return $str;
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
            //For each entry make the data lowercase, required for array_multisort
            for ($dd=0;$dd<count($ar[$q]);$dd++) 
                $ar_lo[$q][$dd] = strtolower($ar[$q][$dd]);    
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
		  // reduce to lowercase
		  for ($dd=0;$dd<count($ar[$q]);$dd++) 
		      $ar_lo[$q][$dd] = strtolower($ar[$q][$dd]);
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
		  // reduce to lowercase
		  for ($dd=0;$dd<count($ar[$q]);$dd++) 
		      $ar_lo[$q][$dd] = strtolower($ar[$q][$dd]);
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


