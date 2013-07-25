<?php
session_start();
//if(!isset($_SESSION['name']))
//{
//header('location: dashboard.php');
//}
include("connect2db.php");
include("header.php");

//Retreve selections or set to defaults
if ($_GET["tally"] != null) 
    $tally = $_GET["tally"];
else 
    $tally = 'album';

if ($_GET["time"] != null)
    $datestring = $_GET["time"];
else
    $datestring = date('Y-m-d H:i:s');
    
$sql_date_string = " AND submittime BETWEEN  \"" . $datestring . "\" AND SUBDATE(\"" . $datestring . "\", INTERVAL 4 WEEK)";

echo '<div id="left1">
<h3>Giga Spin List v1.1</h3>';
echo '    <form action="countdown.php" method="get">';

echo '    <p><input type="radio" name="tally" value="album"';if ($tally=='album') echo 'Checked';echo ' /> Album ';
echo '    <input type="radio" name="tally" value="song"';if ($tally=='song') echo 'Checked';echo ' /> Song</p>';
echo '    <input type="text" name="time" value="' . $datestring . '" />';

echo '<input type="submit" value="Tabulate!"/></p>';
echo '</form>';
echo '<table>';

$samplecount = 100;


//Tally by album assuming no song data wanted
if ($tally == 'album')
{
    echo '<tr><td>&nbsp</td><td>Artist</td><td>Album</td><td align="center">Coundown Weight</td><td align="center">4 Weeks</td><td align="center">3 Weeks</td><td align="center">2 Weeks</td><td align="center">Last Week</td></tr>';
    // Determine the number of distinct artist and album entries
    $query = "SELECT DISTINCT artist, album FROM gigaspinlist WHERE 1 AND submittime BETWEEN  SUBDATE(\"" . $datestring . "\", INTERVAL 4 WEEK)  AND \"" . $datestring . "\"";
    $max = mysql_num_rows(mysql_query($query));
    // For each combination
    for ($i=0; $i<$max; $i++)
        {
            // Query the database for each distinct album and artist entry
            $scanone = "SELECT DISTINCT artist, album FROM gigaspinlist WHERE 1 AND submittime BETWEEN SUBDATE(\"" . $datestring . "\", INTERVAL 4 WEEK) AND \"" . $datestring . "\" LIMIT $i,1";
            $fetchone = mysql_fetch_row(mysql_query($scanone));
            $spinny[$i][0] = normstring($fetchone[0],1);
            $spinny[$i][1] = normstring($fetchone[1],0);
        }
    foreach ($spinny as &$entry)
    {
                // count the number of distinct album and arist entries in the database for this entry
                $artist = strtoupper(str_replace("'","\'",$entry[0]));
                $album = strtoupper(str_replace("'","\'",$entry[1]));
                $counter = "SELECT DISTINCT submittime FROM gigaspinlist WHERE upper(artist) = '$artist' AND upper(album) = '$album' AND submittime BETWEEN  SUBDATE(\"" . $datestring . "\", INTERVAL 4 WEEK) AND \"" . $datestring . "\"";
                $count = mysql_num_rows(mysql_query($counter));
                // Record number of plays for the artist
                $entry[2] = $count;
    }
    
    $spinny = sortmultiarray($spinny);
    
    $break=false;
    $i=0;
    while (!$break)
    {
        $index[$i][0] = $spinny[$i][0];
        $index[$i][1] = $spinny[$i][1];
        $index[$i][2] = $spinny[$i][2];
        if ($i>($samplecount-1) && $spinny[$i][2]>$spinny[$i+1][2]) $break = true;
        else $i++;
    }

    foreach ($index as &$entry)
    {
        // count the number of distinct album and arist entries in the database for this entry
        $artist = strtoupper(str_replace("'","\'",$entry[0]));
        $album = strtoupper(str_replace("'","\'",$entry[1]));
        $counter = "SELECT DISTINCT submittime FROM gigaspinlist WHERE upper(artist) = '$artist' AND upper(album) = '$album' AND submittime BETWEEN SUBDATE(\"" . $datestring . "\", INTERVAL 3 WEEK)  AND \"" . $datestring . "\"";
        $count = mysql_num_rows(mysql_query($counter));
        $entry[3] = $count;
        $entry[2] = $entry[2] - $entry[3];
    }
    
    foreach ($index as &$entry)
    {
        // count the number of distinct album and arist entries in the database for this entry
        $artist = strtoupper(str_replace("'","\'",$entry[0]));
        $album = strtoupper(str_replace("'","\'",$entry[1]));
        $counter = "SELECT DISTINCT submittime FROM gigaspinlist WHERE upper(artist) = '$artist' AND upper(album) = '$album' AND submittime BETWEEN SUBDATE(\"" . $datestring . "\", INTERVAL 2 WEEK) AND \"" . $datestring . "\"";
        $count = mysql_num_rows(mysql_query($counter));
        $entry[4] = $count;
        $entry[3] = $entry[3] - $entry[4];
    }
    foreach ($index as &$entry)
    {
        // count the number of distinct album and arist entries in the database for this entry
        $artist = strtoupper(str_replace("'","\'",$entry[0]));
        $album = strtoupper(str_replace("'","\'",$entry[1]));
        $counter = "SELECT DISTINCT submittime FROM gigaspinlist WHERE upper(artist) = '$artist' AND upper(album) = '$album' AND submittime BETWEEN SUBDATE(\"" . $datestring . "\", INTERVAL 1 WEEK) AND \"" . $datestring . "\"";
        $count = mysql_num_rows(mysql_query($counter));
        $entry[5] = $count;
        $entry[4] = $entry[4] - $entry[5];
    }
    
   
        
    foreach ($index as $key => $entry)
    {
    	for ($i=2;$i<6;$i++)
    	{
        	$weekdata[$i][$key]= $entry[$i];
    	}
    }
    
    foreach ($weekdata as $key => $week)
    {
    	$std[$key] = sd($week);
    	$avg[$key] = array_sum($week)/count($week);
    }
    
    foreach ($index as &$entry)
    {
        if ($std[5] != 0 && $std[5] != null) $entry[6] = ($entry[5]-$avg[5])*1.5/$std[5];
        if ($std[4] != 0 && $std[4] != null) $entry[6] = $entry[6] + ($entry[4]-$avg[4])/$std[4];
        if ($std[3] != 0 && $std[3] != null) $entry[6] = $entry[6] + ($entry[3]-$avg[4])*.5/$std[3];
        if ($std[2] != 0 && $std[2] != null) $entry[6] = $entry[6]+ ($entry[2]-$avg[2])*.25/$std[2];
        $temp = $entry[2];
        $entry[2] = $entry[6];
        $entry[6] = $temp;
    }
    
    $index = sortmultiarray($index);

for ($i=0; $i<count($index); $i++)
    {
        $j = $i + 1;
        echo '<tr><td>' . $j . '</td><td>' . $index[$i][0] . '</td><td>' . $index[$i][1] . '</td><td align="center">' . number_format($index[$i][2],2) . '</td><td align="center">' . $index[$i][6] . '</td><td align="center">' . $index[$i][3] . '</td><td align="center">' . $index[$i][4] . '</td><td align="center">' . $index[$i][5] . '</td></tr>';
    }
}





//Tally by album assuming no song data wanted
if ($tally == 'song')
{
    echo '<tr><td>&nbsp</td><td>Artist</td><td>Album</td><td>Song</td><td align="center">Coundown Weight</td><td align="center">4 Weeks</td><td align="center">3 Weeks</td><td align="center">2 Weeks</td><td align="center">Last Week</td></tr>';
    // Determine the number of distinct artist and album entries
    $query = "SELECT DISTINCT artist, album, track FROM gigaspinlist WHERE 1 AND submittime BETWEEN  \"" . $datestring . "\" AND SUBDATE(\"" . $datestring . "\", INTERVAL 4 WEEK)";
    $max = mysql_num_rows(mysql_query($query));
    // For each combination
    for ($i=0; $i<$max; $i++)
        {
            // Query the database for each distinct album and artist entry
            $scanone = "SELECT DISTINCT artist, album, track FROM gigaspinlist WHERE 1 AND submittime BETWEEN  \"" . $datestring . "\" AND SUBDATE(\"" . $datestring . "\", INTERVAL 4 WEEK) LIMIT $i,1";
            $fetchone = mysql_fetch_row(mysql_query($scanone));
            $spinny[$i][0] = normstring($fetchone[0],1);
            $spinny[$i][1] = normstring($fetchone[1],0);
            $spinny[$i][7] = normstring($fetchone[2],0);
        }
    foreach ($spinny as &$entry)
    {
                // count the number of distinct album and arist entries in the database for this entry
                $artist = strtoupper(str_replace("'","\'",$entry[0]));
                $album = strtoupper(str_replace("'","\'",$entry[1]));
                $track = strtoupper(str_replace("'","\'",$entry[7]));
                $counter = "SELECT * FROM gigaspinlist WHERE upper(artist) = '$artist' AND upper(album) = '$album' AND upper(track) = '$track' AND submittime BETWEEN  \"" . $datestring . "\" AND SUBDATE(\"" . $datestring . "\", INTERVAL 4 WEEK)";
                $count = mysql_num_rows(mysql_query($counter));
                // Record number of plays for the artist
                $entry[2] = $count;
    }
    
    $spinny = sortmultiarray($spinny);
    
    $break=false;
    $i=0; $j=0;
    while (!$break)
    {
        if ($spinny[$j][7] != 'A')
        {
        $index[$i][0] = $spinny[$j][0];
        $index[$i][1] = $spinny[$j][1];
        $index[$i][2] = $spinny[$j][2];
        $index[$i][7] = $spinny[$j][7];
        if ($i>19 && $spinny[$j][2]>$spinny[$j+1][2]) $break = true;
        else $i++;
        }
        $j++;
    }

    foreach ($index as &$entry)
    {
        // count the number of distinct album and arist entries in the database for this entry
        $artist = strtoupper(str_replace("'","\'",$entry[0]));
        $album = strtoupper(str_replace("'","\'",$entry[1]));
        $track = strtoupper(str_replace("'","\'",$entry[7]));
        $counter = "SELECT * FROM gigaspinlist WHERE upper(artist) = '$artist' AND upper(album) = '$album' AND upper(track) = '$track' AND submittime BETWEEN  \"" . $datestring . "\" AND SUBDATE(\"" . $datestring . "\", INTERVAL 3 WEEK)";
        $count = mysql_num_rows(mysql_query($counter));
        $entry[3] = $count;
        $entry[2] = $entry[2] - $entry[3];
    }
    
    foreach ($index as &$entry)
    {
        // count the number of distinct album and arist entries in the database for this entry
        $artist = strtoupper(str_replace("'","\'",$entry[0]));
        $album = strtoupper(str_replace("'","\'",$entry[1]));
        $track = strtoupper(str_replace("'","\'",$entry[7]));
        $counter = "SELECT * FROM gigaspinlist WHERE upper(artist) = '$artist' AND upper(album) = '$album' AND upper(track) = '$track' AND submittime BETWEEN  \"" . $datestring . "\" AND SUBDATE(\"" . $datestring . "\", INTERVAL 2 WEEK)";
        $count = mysql_num_rows(mysql_query($counter));
        $entry[4] = $count;
        $entry[3] = $entry[3] - $entry[4];
    }
    foreach ($index as &$entry)
    {
        // count the number of distinct album and arist entries in the database for this entry
        $artist = strtoupper(str_replace("'","\'",$entry[0]));
        $album = strtoupper(str_replace("'","\'",$entry[1]));
        $track = strtoupper(str_replace("'","\'",$entry[7]));
        $counter = "SELECT * FROM gigaspinlist WHERE upper(artist) = '$artist' AND upper(album) = '$album' AND upper(track) = '$track' AND submittime BETWEEN  \"" . $datestring . "\" AND SUBDATE(\"" . $datestring . "\", INTERVAL 1 WEEK)";
        $count = mysql_num_rows(mysql_query($counter));
        $entry[5] = $count;
        $entry[4] = $entry[4] - $entry[5];
    }
    
    foreach ($index as $key => $entry)
    {
    	for ($i=2;$i<6;$i++)
    	{
        	$weekdata[$i][$key]= $entry[$i];
    	}
    }
    
    foreach ($weekdata as $key => $week)
    {
    	$std[$key] = sd($week);
    	$avg[$key] = array_sum($week)/count($week);
    }
    
    foreach ($index as &$entry)
    {
        if ($std[5] != 0 && $std[5] != null) $entry[6] = ($entry[5]-$avg[5])*1.5/$std[5];
        if ($std[4] != 0 && $std[4] != null) $entry[6] = $entry[6] + ($entry[4]-$avg[4])/$std[4];
        if ($std[3] != 0 && $std[3] != null) $entry[6] = $entry[6] + ($entry[3]-$avg[4])*.5/$std[3];
        if ($std[2] != 0 && $std[2] != null) $entry[6] = $entry[6]+ ($entry[2]-$avg[2])*.25/$std[2];
        $temp = $entry[2];
        $entry[2] = $entry[6];
        $entry[6] = $temp;
    }
    
    $index = sortmultiarray($index);

for ($i=0; $i<count($index); $i++)
    {
        $j = $i + 1;
        echo '<tr><td>' . $j . '</td><td>' . $index[$i][0] . '</td><td>' . $index[$i][1] . '</td><td>' . $index[$i][7] . '</td><td align="center">' . number_format($index[$i][2],2) . '</td><td align="center">' . $index[$i][6] . '</td><td align="center">' . $index[$i][3] . '</td><td align="center">' . $index[$i][4] . '</td><td align="center">' . $index[$i][5] . '</td></tr>';
    }
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
function sortmultiarray($ar)
{
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
return $ar;
}

// Function to calculate square of value - mean
function sd_square($x, $mean) { return pow($x - $mean,2); }

// Function to calculate standard deviation (uses sd_square)    
function sd($array) {
    
// square root of sum of squares devided by N-1
return sqrt(array_sum(array_map("sd_square", $array, array_fill(0,count($array), (array_sum($array) / count($array)) ) ) ) / (count($array)-1) );
}

?>


