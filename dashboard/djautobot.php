<?php
//session_start();
//if(!isset($_SESSION['name']))
//{
//header('location: dashboard.php');
//}

include("connect2db.php");
include("header.php");
echo "
<style>
	.currentrackrow{
	font-weight:bold;
	}
</style>
";

$formdate=$_GET['date'];
$formhour=$_GET['hour'];
$formminute=$_GET['minute'];
$formmysql="$formdate $formhour:$formminute:00";

if ($formdate==null || $formhour== null || $formminute==null) 
{
	$formall = time()-900;
	$default = true;
}	// if one is void default to 15 min ago
else {$formall = strtotime($formmysql);} // if not combine str values and get php time from values

for ($i=0;$i<7;$i++)
{
	$daysoftheweek[$i][0]=time()-($i*86400);
	$daysoftheweek[$i][1]=date("l",time()-($i*86400)); 		//store the text of the day of the week for each day working backwards
	$daysoftheweek[$i][2]=date("F",time()-($i*86400));		//store the text of the Month
	$daysoftheweek[$i][3]=date("jS",time()-($i*86400));		//store the text of the #th day
	$daysoftheweek[$i][4]=date("Y-m-d",time()-($i*86400));
}	


echo '<div id="left1">';
echo '<form action="djautobot.php" method="get">';
echo "<select name=\"date\">";
foreach ($daysoftheweek as $key => $day)
{
echo	"<option value=\"$day[4]\""; if($day[4]==$formdate){echo " selected=\"true\"";} echo ">$day[1] $day[2] $day[3]</option>";
}
echo "</select>";

echo "<select name=\"hour\">";
for($i=0;$i<24;$i++)
{
	echo "<option value=\"$i\""; if($i==date("H",$formall)){echo " selected=\"true\"";} echo ">$i</option>";
}
echo "</select>";

echo "<select name=\"minute\">";
for($i=0;$i<60;$i++)
{
	echo "<option value=\"$i\""; if($i==date("i",$formall)){echo " selected=\"true\"";} echo ">$i</option>";
}
echo "</select>";
echo '<input type="submit" value="View Time">';
echo "</form>";

include('connect2db.php');
{
	echo '<table>';
	echo '<tr><th>Timestamp</th><th>Track</th><th>Artist</th><th>Album</th></tr>';
	$query = 'SELECT timestamp, track, artist, album FROM autobot WHERE timestamp BETWEEN SUBDATE("'.date("Y-m-d H:i:s").'", INTERVAL 1 WEEK) AND "'.date("Y-m-d H:i:s").'" ORDER BY timestamp DESC';
	$query_ = mysql_query($query);
	$max = mysql_num_rows($query_);
	for ($i =0; $i<$max; $i++)
	{
		$fetch = mysql_fetch_row($query_);
		$tracks[$i][0] = $fetch[0];
		if ($fetch[1] == null)
			$tracks[$i][1] = 'No Track Name';
		else
			$tracks[$i][1] = $fetch[1];
		if ($fetch[2] == null)
			$tracks[$i][2] = 'No Artist Name';
		else
			$tracks[$i][2] = $fetch[2];
		if ($fetch[3] == null)
			$tracks[$i][3] = 'No Album Name';
		else
			$tracks[$i][3] = $fetch[3];
		$tracks[$i][4] = strtotime($fetch[0]);
	}
	foreach($tracks as $key => $track)
	{
		if (abs($track[4]-$formall)<1200)
		{
			$closetracks[$key][0] = $track[0];
			$closetracks[$key][1] = $track[1];
			$closetracks[$key][2] = $track[2];
			$closetracks[$key][3] = $track[3];
			$closetracks[$key][4] = $track[4];
			$closetracks[$key][5] = $track[4]-$formall;  
		}
	}
	foreach($closetracks as $key => $track)
	{
		if($track[5]<0)
		{
			$closetracks[$key][6] = true;
			break;
		}
	}
	foreach($closetracks as $track)
	{
		echo '<tr ';
		if ($track[6] && !($default)) echo 'class="currentrackrow" >'; else echo '>';
		echo "<td>$track[0]</td><td>$track[1]</td><td>$track[2]</td><td>$track[3]</td>";
		echo "</tr>";

	}
	echo "</table>";
}

echo '</div>';
include('footer.php');
?>