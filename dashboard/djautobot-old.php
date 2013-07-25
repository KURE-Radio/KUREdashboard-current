<?php
session_start();
if(!isset($_SESSION['name']))
{
header('location: dashboard.php');
}

include("connect2db.php");
include("header.php");

echo '<div>';
include('connect2db.php');
{
	echo '<table>';
	echo '<tr><th>Timestamp</th><th>Track</th><th>Artist</th><th>Album</th></tr>';
	$query = 'SELECT timestamp, track, artist, album FROM autobot ORDER BY timestamp LIMIT 0, 100';
	$query_ = mysql_query($query);
	$max = mysql_num_rows($query_);
	for ($i =0; $i<$max; $i++)
	{
		$fetch = mysql_fetch_row($query_);
		$timestamp = $fetch[0];
		if ($fetch[1] == null)
			$track = 'No Track Name';
		else
			$track = $fetch[1];
		if ($fetch[2] == null)
			$artist = 'No Artist Name';
		else
			$artist = $fetch[2];
		if ($fetch[3] == null)
			$album = 'No Album Name';
		else
			$album = $fetch[3];
		echo "<tr><td>$timestamp</td><td>$track</td><td>$artist</td><td>$album</td></tr>";
	}
	echo "</table>";
}

echo '</div>';
include('footer.php');
?>