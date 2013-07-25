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
        $sql_date_string = " AND submittime >= SUBDATE(CURRENT_TIMESTAMP, INTERVAL 1 YEAR)";
        break;
    case 5:
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
echo '        <OPTION ';if ($time==4) echo 'SELECTED'; echo ' value="4">1 Year Ago';
echo '        <OPTION ';if ($time==5) echo 'SELECTED'; echo ' value="5">All Time';
echo '    </SELECT>';
echo '<input type="submit" value="Tabulate!"/></p>';
echo '</form>';
echo '<br/><br/><table>';




//Retrieve all entries within specified date
$query = "SELECT artist, album, submittime, track FROM gigaspinlist WHERE 1" . $sql_date_string;
$result = mysql_query($query);
//Determine number of entries 
$max = mysql_num_rows($result);


for ($i=0; $i<$max; $i++)
        {
			//Fetch the next (or starting, for $i=0) row in the selection
            $fetchone = mysql_fetch_row($result);
			//Assign all values in row to a single concat'ed string
//		if($fetchone[0] != "" && $fetchone[1] != "")
//			{
           		$spinnies[$i] = $fetchone[0] . "%%%" . $fetchone[1] . "%%%" . $fetchone[2];
//			}
        }
		
		//Count the frequency of each unique entry in $spinnies, and assign said frequency to a value in a new array, with $spinnies values as the indices
		$spinnies = array_unique($spinnies);
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
		$explosions = explode("%%%",$spinnies[$j]);
		$spinny[$j][0] = $explosions[0];
		$spinny[$j][1] = $explosions[1];
		$spinny[$j][2] = $spincounts[$spinnies[$j]];
		}
		
$spinny = sortmultiarray($spinny,$sort);

//Print out the results
if ($sort = 'count')
{
    for ($i=0; $i<$uniquecombos; $i++)
    {
        $j = $i + 1;
        echo '<tr><td>' . $j . '</td><td>' . $spinny[$i][0] . '</td><td>' . $spinny[$i][1] . '</td><td>' . $spinny[$i][3] . '</td><td>' . $spinny[$i][2] . '</td></tr>';
    }
}
else
{
    for ($i=0; $i<$uniquecombos; $i++) echo '<tr><td>' . $spinny[$i][0] . '</td><td>' . $spinny[$i][1] . '</td><td>' . $spinny[$i][3] . '</td><td>' . $spinny[$i][2] . '</td></tr>';
}

echo "</table></div>";


include("footer.php");
?>
