<?php
require "twitch.php";

$id = $_REQUEST['id'];
$set = 0;
//print $id;
$sname = getstreamername($id);
//print "<BR>";
//print $sname;
if ($sname == "") {
	exit(-1);
}

$_SESSION['streamer'] = $sname;
$_SESSION['streamerid'] = $id;

//exit(0);
?>


<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title> speechRecog </title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Architects+Daughter|Dosis'>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="./style.css">
<script>
	var streamer = "<?php echo $_SESSION['streamer']; ?>";
	var streamerid = "<?php echo $_SESSION['streamerid']; ?>";
</script>
</head>
<body>
<!-- partial:index.partial.html -->
<?php echo $sname; ?><br>
<div class="container">
	<div class="paper">
	</div>
</div>
<!-- partial -->
  <script  src="./script.js"></script>

</body>
</html>
