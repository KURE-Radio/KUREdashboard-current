<?php
session_start();
if(!isset($_SESSION['name']))
{
header('location: dashboard.php');
}

include("connect2db.php");
include("header.php");
?>

</script>

<div width="100%" align="center">
<h3>Bug In the System v1.0</h3>
<form action="submitbug.php" method="post">
<?php 
echo
'<input type="hidden" name="name" value="' . $_SESSION['name'] . '"/>
<input type="hidden" name="email" value="' . $_SESSION['email'] . '"/>';
?>

I Am: &nbsp; &nbsp;<select name="option"><option value="Bug In the System">Reporting a Bug</option><option value="Idea For the System">Submitting an Idea</option><option value="Question About the System">Asking a Question</option></select>


<h3>Type your bug/question/idea here:</h3>
<textarea resize="none" cols="100" rows="20" name="body"></textarea><br/>
<div>&nbsp;</div>

<input type="submit" style="width: 400px" />


<br/>
</form>


</div>

<?php
include("footer.php");
?>