<?php


//Shit to add:
// Limit By # (Default to top 50?)
// Selectable Date
// Fix tally and sort by options

include("header.php");
include("connect2db.php");
include("functions.php");

echo '<div id="left1">';

if ($_GET["sort"] != null)
    $sort = $_GET["sort"];
else
    $sort = 'count';

if ($_GET["tally"] != null)
    $tally = $_GET["tally"];
else
    $tally = 'album';

if ($_GET["type"] != null)
    $type = $_GET["type"];
else
    $type = 'spinny';

if ($type == 'spinny')
	$coe = array(.000001,.0001,.01,1.0); 
elseif($type == 'countdown')
	$coe = array(.25,.5,1.0,1.5);

if ($_GET["time"] != null)
    $datestring = $_GET["time"];
else
    $datestring = date('Y-m-d H:i:s');

//if ($_GET["time"] != null)
//    $time = (int) $_GET["time"];

$sql_date_string = " AND submittime BETWEEN SUBDATE(\"$datestring\", INTERVAL 4 WEEK) AND \"$datestring\"";

/*switch ($time)
{
    case 1:
        $sql_date_string = " AND submittime >= SUBDATE(CURRENT_TIMESTAMP, INTERVAL 4 WEEK)";
        break;
    case 2:
        $sql_date_string = " AND submittime >= SUBDATE(CURRENT_TIMESTAMP, INTERVAL 1 MONTH)";
        break; case 3:
        $sql_date_string = "";
        break;
}
*/


echo '    <form action="cd3.php" method="get">';

echo '    <p>Tally By:'; 
//echo '    <input type="radio" name="tally" value="artist"';if ($tally=='artist') echo 'Checked';echo ' /> Artist';
echo '    <input type="radio" name="tally" value="album"';if ($tally=='album') echo 'Checked';echo ' /> Album ';
echo '    <input type="radio" name="tally" value="song"';if ($tally=='song') echo 'Checked';echo ' /> Song</p>';

echo '    <p>Tally Type:'; 
echo '    <input type="radio" name="type" value="spinny"';if ($type=='spinny') echo 'Checked';echo ' /> Spinlist ';
echo '    <input type="radio" name="type" value="countdown"';if ($type=='countdown') echo 'Checked';echo ' /> Countdown</p>';

//echo '    <p>Sort By: 
//	  <input type="radio" name="sort" value="artist"';if ($sort=='artist') echo 'Checked';echo ' /> Artist';
//echo '    <input type="radio" name="sort" value="album"';if ($sort=='album') echo 'Checked';echo ' /> Album';
//echo '    <input type="radio" name="sort" value="song"';if ($sort=='song') echo 'Checked';echo ' /> Song';
//echo '    <input type="radio" name="sort" value="count"';if ($sort=='count') echo 'Checked';echo ' /> Tally</p>';

//echo '    <p>Track Plays Since: ';
//echo '    <SELECT name="time">';
//echo '        <OPTION ';if ($time==1) echo 'SELECTED'; echo ' value="1"> 1 Week Ago';
//echo '        <OPTION ';if ($time==2) echo 'SELECTED'; echo ' value="2">1 Month Ago';
//echo '        <OPTION ';if ($time==3) echo 'SELECTED'; echo ' value="3">All Time';
//echo '    </SELECT>';
echo '    <input type="text" name="time" value="' . $datestring . '" />';
echo '<input type="submit" value="Tabulate!"/></p>';
echo '</form>';

echo '<br/><br/><table>';
echo '<tr><td>&nbsp</td><td>Artist</td><td>Album</td><td align="center">Weight</td><td align="center">4 Weeks</td><td align="center">3 Weeks</td><td align="center">2 Weeks</td><td align="center">Last Week</td></tr>';


//Retrieve all entries from the last month
$query = "SELECT artist, album, submittime, track FROM gigaspinlist WHERE 1" . $sql_date_string;
$result = mysql_query($query);
//Determine number of entries 

$max = mysql_num_rows($result);

for ($i=0; $i<$max; $i++)
{
	$fetch = mysql_fetch_row($result);
	if ($tally=='album')
	$spins[$i] = $fetch[0] . "%%%" . $fetch[1] . "%%%" . $fetch[2];
	elseif ($tally=='song')
	$spins[$i] = $fetch[0] . "%%%" . $fetch[3] . "%%%" . $fetch[2];
}

//Only look at unique artist/album/timestamp entries
$spins = array_unique($spins);
//Put each entry into one of the last four weeks by timestamp and put it in a master list
foreach ($spins as $key => $spin)
{
	$exploded = explode('%%%',$spin);
	$submittime = strtotime($exploded[2]);

	//Remove any null, empty or space values
	if(	(($exploded[0] == "" && $exploded[1] == "") ||
		($exploded[0] == " " && $exploded[1] == " ") ||
		($exploded[0] == null && $exploded[1] == null)))	
		
		unset($spins[$key]);	
	else	
	{
		if($submittime >= strtotime('-1 week'))
			$week1[$key] = $exploded[0] . '%%%' . $exploded[1];

		elseif(($submittime >= strtotime('-2 week')) && ($submittime < strtotime('-1 week')))
			$week2[$key] = $exploded[0] . '%%%' . $exploded[1];

		elseif(($submittime >= strtotime('-3 week')) && ($submittime < strtotime('2 week')))
			$week3[$key] = $exploded[0] . '%%%' . $exploded[1];

		else
			$week4[$key] = $exploded[0] . '%%%' . $exploded[1];

		$spins[$key] = $exploded[0] . '%%%' . $exploded[1];		
	}	

}
//Now that timestamp information has been used and disgarded reasign key values for the weekly arrays and discard duplicates in the master list.
$week1 = array_values($week1);
$week2 = array_values($week2);
$week3 = array_values($week3);
$week4 = array_values($week4);
$spins = array_unique($spins);
$spins = array_values($spins);
//Count values in each week
$week1counts = array_count_values($week1);
$week2counts = array_count_values($week2);
$week3counts = array_count_values($week3);
$week4counts = array_count_values($week4);
//Assembled final array with Artist, Album, null for score, any and all week counts
foreach($spins as $key => $spin)
{
	$exploded = explode('%%%', $spin);
	$assembled_spins[$key][0] = $exploded[0];
	$assembled_spins[$key][1] = $exploded[1];
	$assembled_spins[$key][2] = null;
	$assembled_spins[$key][3] = $week4counts[$spin];
	$assembled_spins[$key][4] = $week3counts[$spin]; 
	$assembled_spins[$key][5] = $week2counts[$spin];
	$assembled_spins[$key][6] = $week1counts[$spin];
	for($i=3; $i<7; $i++)
		if ($assembled_spins[$key][$i] == null) $assembled_spins[$key][$i] = 0;

}
//Get values for std and average for each week
foreach($assembled_spins as $key => $spin)
{
        for($j=3; $j<7; $j++)
        {
                $weekarr[$j][$key] = $spin[$j];
        }
}
for($i=3; $i<7; $i++)
{
        $count = 0;
        foreach($weekarr[$i] as $tally)
        {
                if ($tally != 0) $count++;
        }
        $mathdata[$i][0] = array_sum($weekarr[$i])/$count;
        $mathdata[$i][1] = sd($weekarr[$i]);
}
foreach($assembled_spins as &$spin)
{
        if($mathdata[3][1] !=0 && $mathdata[3][1] != null) $spin[2] = ($spin[3]-$mathdata[3][0])*$coe[0]/$mathdata[3][0];
        if($mathdata[4][1] !=0 && $mathdata[4][1] != null) $spin[2] += ($spin[4]-$mathdata[4][0])*$coe[1]/$mathdata[4][0];
        if($mathdata[5][1] !=0 && $mathdata[5][1] != null) $spin[2] += ($spin[5]-$mathdata[5][0])*$coe[2]/$mathdata[5][0];
        if($mathdata[6][1] !=0 && $mathdata[6][1] != null) $spin[2] += ($spin[6]-$mathdata[6][0])*$coe[3]/$mathdata[6][0];

}

$assembled_spins = sortmultiarray($assembled_spins,$sort);

for ($i=0; $i<count($assembled_spins); $i++)
{
	$j = $i + 1;
        echo '<tr><td>' . $j . '</td><td>' . $assembled_spins[$i][0] . '</td><td>' . $assembled_spins[$i][1] . '</td><td align="center">' . number_format($assembled_spins[$i][2],2) . '</td><td align="center">' . $assembled_spins[$i][3] . '</td><td align="center">' . $assembled_spins[$i][4] . '</td><td align="center">' . $assembled_spins[$i][5] . '</td><td align="center">' . $assembled_spins[$i][6] . '</td></tr>';
}

echo "</table></div>";


include("footer.php");
// Function to calculate square of value - mean
function sd_square($x, $mean) { return pow($x - $mean,2); }

// Function to calculate standard deviation (uses sd_square)    
function sd($array) {

// square root of sum of squares devided by N-1
return sqrt(array_sum(array_map("sd_square", $array, array_fill(0,count($array), (array_sum($array) / count($array)) ) ) ) / (count($array)-1) );
}
?>
                                                                                                                                                                                                                                                                   331,2         Bot

