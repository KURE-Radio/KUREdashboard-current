<?php


//Shit to add:
// Limit By # (Default to top 50?)
// Selectable Date
// Fix tally and sort by options

include("header.php");
include("connect2db.php");
include("functions.php");

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
<h3>Giga Chart v2.0</h3>';
echo '    <form action="spinny.php" method="get">';

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
echo '<br/><br/><table>';
echo '<tr><td>&nbsp</td><td>Artist</td><td>Album</td><td align="center">Coundown Weight</td><td align="center">4 Weeks</td><td align="center">3 Weeks</td><td align="center">2 Weeks</td><td align="center">Last Week</td></tr>';



//Retrieve all entries from the last month
$sql_date_string = " AND submittime >= SUBDATE(CURRENT_TIMESTAMP, INTERVAL 4 WEEK)";
$query = "SELECT artist, album, submittime, track FROM gigaspinlist WHERE 1" . $sql_date_string;
$result = mysql_query($query);
//Determine number of entries 
$max = mysql_num_rows($result);


for ($i=0; $i<$max; $i++)
        {
		$fetchone = mysql_fetch_row($result);
		$spinnies[$i] = $fetchone[0] . "%%%" . $fetchone[1] . "%%%" . $fetchone[2];
        }
		
		//Count the frequency of each unique entry in $spinnies, and assign said frequency to a value in a new array, with $spinnies values as the indices
		$spinnies = array_unique($spinnies);
		foreach ($spinnies as $key => $spin)
		{
			$exploded = explode('%%%',$spin);	
			$submittime = strtotime($exploded[2]);	
			if($submittime > strtotime('-1 week'))
				$oneweek[$key] = $spin;	
			elseif(($submittime > strtotime('-2 week')) && ($submittime <= strtotime('-1 week')))	
				$twoweek[$key] = $spin;
			elseif(($submittime > strtotime('-3 week')) && ($submittime <= strtotime('-2 week')))	
				$threeweek[$key] = $spin;
			else
				$fourweek[$key] = $spin;
			
			$spinny[$key] = $exploded[0] . '%%%' . $exploded[1];

		}
		$spinny = array_values($spinny);	
		$spinny = array_unique($spinny);
		$spinnies = array_values($fourweek);
		for($j=0; $j<count($spinnies); $j++)
		{
			$explosions = explode("%%%",$spinnies[$j]);
			$spinnies[$j] = $explosions[0] . "%%%" . $explosions[1];
		}
		$spincounts = array_count_values($spinnies);
		//Count the number of unique combinations of artist and album
		$uniquecombos = count($spincounts);
		//Remove duplicate and empty entries in the original $spinnies array
		$spinnies = array_unique($spinnies);
		foreach($spinnies as $key => $value) 
		{ 
			if($value == "") { 
				unset($spinnies[$key]); 
			} 
		} 
		$spinnies = array_values($spinnies); 
		
for ($j=0; $j<$uniquecombos; $j++)
		{
		$spinny4[$j][0] = $spinnies[$j];
		$spinny4[$j][1] = null;
		$spinny4[$j][2] = $spincounts[$spinnies[$j]];
		}

		$spinnies = array_values($threeweek);
		for($j=0; $j<count($spinnies); $j++)
		{
			$explosions = explode("%%%",$spinnies[$j]);
			$spinnies[$j] = $explosions[0] . "%%%" . $explosions[1];
		}
		$spincounts = array_count_values($spinnies);
		//Count the number of unique combinations of artist and album
		$uniquecombos = count($spincounts);
		//Remove duplicate and empty entries in the original $spinnies array
		$spinnies = array_unique($spinnies);
		foreach($spinnies as $key => $value) 
		{ 
			if($value == "") { 
				unset($spinnies[$key]); 
			} 
		} 
		$spinnies = array_values($spinnies); 
		
for ($j=0; $j<$uniquecombos; $j++)
		{
		$spinny3[$j][0] = $spinnies[$j];
		$spinny3[$j][1] = null;
		$spinny3[$j][2] = $spincounts[$spinnies[$j]];
		}

		$spinnies = array_values($twoweek);
		for($j=0; $j<count($spinnies); $j++)
		{
			$explosions = explode("%%%",$spinnies[$j]);
			$spinnies[$j] = $explosions[0] . "%%%" . $explosions[1];
		}
		$spincounts = array_count_values($spinnies);
		//Count the number of unique combinations of artist and album
		$uniquecombos = count($spincounts);
		//Remove duplicate and empty entries in the original $spinnies array
		$spinnies = array_unique($spinnies);
		foreach($spinnies as $key => $value) 
		{ 
			if($value == "") { 
				unset($spinnies[$key]); 
			} 
		} 
		$spinnies = array_values($spinnies); 
		
for ($j=0; $j<$uniquecombos; $j++)
		{
		$spinny2[$j][0] = $spinnies[$j];
		$spinny2[$j][1] = null;
		$spinny2[$j][2] = $spincounts[$spinnies[$j]];
		}

		$spinnies = array_values($oneweek);
		for($j=0; $j<count($spinnies); $j++)
		{
			$explosions = explode("%%%",$spinnies[$j]);
			$spinnies[$j] = $explosions[0] . "%%%" . $explosions[1];
		}
		$spincounts = array_count_values($spinnies);
		//Count the number of unique combinations of artist and album
		$uniquecombos = count($spincounts);
		//Remove duplicate and empty entries in the original $spinnies array
		$spinnies = array_unique($spinnies);
		foreach($spinnies as $key => $value) 
		{ 
			if($value == "") { 
				unset($spinnies[$key]); 
			} 
		} 
		$spinnies = array_values($spinnies); 
		
for ($j=0; $j<$uniquecombos; $j++)
		{
		$spinny1[$j][0] = $spinnies[$j];
		$spinny1[$j][1] = null;
		$spinny1[$j][2] = $spincounts[$spinnies[$j]];
		}
	
//foreach artist/album combo that has recieved a play in the last month breakout values into one week intervals
foreach($spinny as $key => $spins)
{
	//foreach artist/ablum combo that has been played in the last three weeks, subtract it's plays from the month total to get 4 weeks ago's total then set it's total for the last three weeks	
	foreach($spinny4 as $spins2)
	{
		if ($spins == $spins2[0])
		{
			$spinnyfinal[$key][0] = $spins;
			$spinnyfinal[$key][1] = null;
			$spinnyfinal[$key][2] = null;
			$spinnyfinal[$key][3] = $spins2[2];
		}		
	}	
	//foreach artist/ablum combo that has been played in the last two weeks, subtract it's plays from the last three week's  total to get 3 weeks ago's total then set it's total for the last two weeks	
	unset($spins2);
	foreach($spinny3 as $spins2)
	{
		if ($spins == $spins2[0])
		{
			$spinnyfinal[$key][4] = $spins2[2];
		}		
	}//foreach artist/ablum combo that has been played in the last two weeks, subtract it's plays from the last three week's  total to get 3 weeks ago's total then set it's total for the last two weeks	
	unset($spins2);
	foreach($spinny2 as $spins2)
	{
		if ($spins == $spins2[0])
		{
			$spinnyfinal[$key][5] = $spins2[2];
		}		
	}	
	//foreach artist/ablum combo that has been played in the last week, subtract it's plays from the last two week's  total to get 2 weeks ago's total then set last week's plays	
		
	unset($spins2);
	foreach($spinny1 as $spins2)
	{
		if ($spins == $spins2[0])
		{
			$spinnyfinal[$key][6] = $spins2[2];
		}		
	}
}

//explode the artist/album replace any null count values with 0 
for($i=0; $i<count($spinnyfinal);$i++)
{
	$exploded = explode("%%%", $spinnyfinal[$i][0]);
	$spinnyfinal[$i][0] = $exploded[0];
	$spinnyfinal[$i][1] = $exploded[1];
}


foreach ($spinnyfinal as $key => $entry)
{
	if(     $entry[0] == "" || $entry[1] == "" ||
                $entry[0] == " " || $entry[1] == " " ||
                $entry[0] == null || $entry[1] == null)
		unset($spinnyfinal[$key]);
}
$spinnyfinal = array_values($spinnyfinal);

for($i=0; $i<count($spinnyfinal);$i++)
{
	for($j=3; $j<7; $j++)
	{
		if($spinnyfinal[$i][$j] == null) $spinnyfinal [$i][$j] = 0;
		$weekarr[$j][$i] = $spinnyfinal[$i][$j];	
	}		
}

for($i=3; $i<7; $i++)
{
	$count = 0;
	foreach($weekarr[$i] as $tally)
	{
		if ($tally != 0 && $tally != null) $count++;	
		
	}
	$mathdata[$i][0] = array_sum($weekarr[$i])/$count;
	$mathdata[$i][1] = sd($weekarr[$i]);
}

foreach($spinnyfinal as &$spin)
{
	if($mathdata[3][1] !=0 && $mathdata[3][1] != null) $spin[2] = ($spin[3]-$mathdata[3][0])*.25/$mathdata[3][0];
	if($mathdata[4][1] !=0 && $mathdata[4][1] != null) $spin[2] += ($spin[4]-$mathdata[4][0])*.5/$mathdata[4][0];
	if($mathdata[5][1] !=0 && $mathdata[5][1] != null) $spin[2] += ($spin[5]-$mathdata[5][0])*1.0/$mathdata[5][0];
	if($mathdata[6][1] !=0 && $mathdata[6][1] != null) $spin[2] += ($spin[6]-$mathdata[6][0])*1.5/$mathdata[6][0];
	 
}

$spinny = sortmultiarray($spinnyfinal,$sort);

//Print out the results
if ($sort = 'count')
{
    for ($i=0; $i<$uniquecombos; $i++)
    {
        $j = $i + 1;
        echo '<tr><td>' . $j . '</td><td>' . $spinny[$i][0] . '</td><td>' . $spinny[$i][1] . '</td><td align="center">' . number_format($spinny[$i][2],2) . '</td><td align="center">' . $spinny[$i][3] . '</td><td align="center">' . $spinny[$i][4] . '</td><td align="center">' . $spinny[$i][5] . '</td><td align="center">' . $spinny[$i][6] . '</td></tr>';
    }
}
else
{
    for ($i=0; $i<$uniquecombos; $i++) echo '<tr><td>' . $spinny[$i][0] . '</td><td>' . $spinny[$i][1] . '</td><td>' . $spinny[$i][3] . '</td><td>' . $spinny[$i][2] . '</td></tr>';
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
