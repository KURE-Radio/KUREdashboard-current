<?php include('./headerheader.php');?>
<div id="menu">
<div class="title dark"><h2><a href="dashboard.php">DJ Dash </a> - 
<?php
if(isset($_SESSION['name']))
{

//echo '<a href="login.php">Transmighty Log </a>';

echo '<a href="playlist.php">Giga Spin List </a> - ';

echo '<a href="djautobot.php">Autobot Playlist </a>';

//echo '<a href="grant.php">Grant Manager </a>';

//echo '<a href="autobot.php">Autobot Submittor </a>';

//echo '<a href="buginthesystem.php">Report A Bug/Request a Feature</a>';
}
?>
</h2>
</div>
</div>



