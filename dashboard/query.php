<?php
session_start();
//if(!isset($_SESSION['name']))
//{
//header('location: dashboard.php');
//}

include("connect2db.php");
include("header.php");
 
echo '<div id="left1">
<h3>Giga Spin List Query Pagev1</h3>';
echo '<form action="query.php" method="post"> 
Artist <input type="text" name="artist"/>  
Album <input type="text" name="album" />  
Track <input type="text" name="track" /> 
Show Full Track Data <input type="checkbox" name="data" value="yes">
<input type="submit" value="Query!"/></form>';


//Retreve Query (if any)
$q_artist = $_POST["artist"];
$q_album = $_POST["album"];
$q_track = $_POST["track"];
$q_data = $_POST["data"];

//Specific Data Requested
if ($q_data != "yes")
{
    $query = "SELECT DISTINCT artist, album, track FROM gigaspinlist";
    $max = mysql_num_rows(mysql_query($query));
    for ($i=0; $i<$max; $i++)
        {
            $scanone = "SELECT DISTINCT artist, album, track FROM gigaspinlist LIMIT $i,1";
            $fetchone = mysql_fetch_row(mysql_query($scanone));
            $artist = strtoupper(str_replace("'","\'",$fetchone[0]));
            $album = strtoupper(str_replace("'","\'",$fetchone[1]));
            $track = strtoupper(str_replace("'","\'",$fetchone[2]));
            $counter = "SELECT * FROM gigaspinlist WHERE upper(artist) = '$artist' AND upper(album) = '$album' AND upper(track) = '$track'";
            $count = mysql_num_rows(mysql_query($counter));
            $spinny[$i][0] = $fetchone[0];
            $spinny[$i][1] = $fetchone[1];
            $spinny[$i][2] = $fetchone[2];
            $spinny[$i][3] = $count;
        }
    
    //Print
        echo '<table><tr><td>Artist</td><td>Album</td><td>Track</td></tr>';
        for ($i=0; $i<$max; $i++)
            {
                if (((strtoupper($spinny[$i][0]) == strtoupper($q_artist)) && ($q_artist != null)) || ((strtoupper($spinny[$i][1]) == strtoupper($q_album)) && ($q_album != null)) || ((strtoupper($spinny[$i][2]) == strtoupper($q_track)) && ($q_track != null)))
                    echo '<tr><td>' . $spinny[$i][0] . '</td><td>' . $spinny[$i][1] . '</td><td>' . $spinny[$i][2] . '</td><td>' . $spinny[$i][3] . '</td></tr>';
            }
}
//Full Data Requested
else
{
    $query = "SELECT * FROM gigaspinlist";
    $max = mysql_num_rows(mysql_query($query));
    for ($i=0; $i<$max; $i++)
        {
            $scanone = "SELECT * FROM gigaspinlist LIMIT $i,1";
            $fetchone = mysql_fetch_row(mysql_query($scanone));
            $spinny[$i][0] = $fetchone[0];
            $spinny[$i][1] = $fetchone[1];
            $spinny[$i][2] = $fetchone[2];
            $spinny[$i][3] = $fetchone[3];
            $spinny[$i][4] = $fetchone[4];
            $spinny[$i][5] = $fetchone[5];
            $spinny[$i][6] = $fetchone[6];
        }
    
    //Print
        echo '<table><tr><td>Track</td><td>Artist</td><td>Album</td><td>Show</td><td>DJ</td><td>Playtime</td><td>Submit Time</td></tr>';
        for ($i=0; $i<$max; $i++)
            {
                if (((strtoupper($spinny[$i][0]) == strtoupper($q_track)) && ($q_track != null)) || ((strtoupper($spinny[$i][1]) == strtoupper($q_artist)) && ($q_artist != null)) || ((strtoupper($spinny[$i][2]) == strtoupper($q_album)) && ($q_album != null)))
                    echo '<tr><td>' . $spinny[$i][0] . '</td><td>' . $spinny[$i][1] . '</td><td>' . $spinny[$i][2] . '</td><td>' . $spinny[$i][3] . '</td><td>' . $spinny[$i][4] . '</td><td>' . $spinny[$i][5] . '</td><td>' . $spinny[$i][6] . '</td></tr>';
            }    
}
echo
'</table>';
echo '</div>';
include("footer.php");
?>


