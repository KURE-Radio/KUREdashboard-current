<?php
session_start();
if(!isset($_SESSION['name']))
{
header('location: dashboard.php');
}
include("connect2db.php");
include("header.php");
?>


<div width="100%" height="100%" align="center">
<h3>Autobot Submittor v1.0</h3>

<iframe width="100%" height="400px" frameborder="no" src="http://www.pinnacle-recording.com/autobot.php" />


</div>

<?php
include("footer.php");
?>