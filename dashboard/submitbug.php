<?php
session_start();

include("connect2db.php");
include("header.php");

$email = $_POST['email'] ;
$message = $_POST['name'] . "\n" . $_POST['body'] ;
$option = $_POST['option'] ;

  mail( "dnhushak@pinnacle-recording.com", $option,
    $message, "From: $email" );

?>

</script>

<div width="100%" align="center">
<h3>Bug In the System v1.0</h3>
<?php 
if ($_POST['name']!="")
	{
	echo "Thanks, " . $_POST['name'] . "!";
	}
	else
	{
	echo "Thanks!";
	}
end
?>  Your submission was sent and will be addressed shortly.

</div>

<?php
include("footer.php");
?>