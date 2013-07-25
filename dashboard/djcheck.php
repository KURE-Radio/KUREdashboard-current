<?php

include("header.php");
include("connect2db.php");
include("functions.php");


if ($_GET["time"] != null)
    $datestring = $_GET["time"];
else
    $datestring = date('Y-m-d H:i:s');

if ($_GET["submittime"] != null)
{
	$checkplaylist = TRUE;
	$dj = $_GET["dj"];
	$submittime = $_GET["submittime"];
}


echo '    <form action="djcheck.php" method="get">';
echo '    <input type="text" name="time" value="' . $datestring . '" />';
echo '<input type="submit" value="Tabulate!"/></p>';
echo '</form>';


echo '<table>';

if($checkplaylist)
{
	echo $dj . $submittime;
	$query = "SELECT track,artist,album  FROM gigaspinlist WHERE submittime=\"$submittime\" " ;
	$result = mysql_query($query);
	$max = mysql_num_rows($result);
	for ($i=0; $i<$max; $i++)
	{
		$fetch = mysql_fetch_row($result);
		echo "<tr><td>$fetch[0]</td><td>$fetch[1]</td><td>$fetch[2]</td></tr>";
	}
}
else{
	$sql_date_string = " AND submittime BETWEEN SUBDATE(\"$datestring\", INTERVAL 1 WEEK) AND \"$datestring\"";
	$query = "SELECT DISTINCT dj,submittime, playtime FROM gigaspinlist   WHERE 1 $sql_date_string ORDER BY  `gigaspinlist`.`submittime` ASC" ;
	$result = mysql_query($query);
	$max = mysql_num_rows($result);
for ($i=0; $i<$max; $i++)
{
	$fetch = mysql_fetch_row($result);
	echo "<tr><td><a href=\"djcheck.php?dj=$fetch[0]&submittime=$fetch[1]\">"; if($fetch[0]==null)echo "null"; else echo $fetch[0]; echo "</a></td><td>$fetch[1]</td><td>$fetch[2]</td></tr>";
}

}

echo '</table>';

