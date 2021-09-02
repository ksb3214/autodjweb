<?php
require "twitch.php";

$cmd = $_REQUEST['cmd'];
$id = $_REQUEST['id'];

$sname = getstreamername($id);

if ($sname == "") {
	exit(-1);
}

$logfile = "log/" . $sname . "-final.log";
file_put_contents($logfile, $cmd . "\n", FILE_APPEND);

$cmd = strtolower($cmd);
$finalcmd = "";

// No trigger word
if (strpos($cmd, "auto dj") === false && 
	strpos($cmd, "alto dj") === false &&
	strpos($cmd, "autodj") === false &&
	strpos($cmd, "also dj") === false) {
	exit(0);
}
// Okay lets parse whata we got...
$fp2 = fopen("/opt/bot/" . $sname . "/voicecommands/commands.dat","r");
if (flock($fp2, LOCK_EX)) {
    while (($line = fgets($fp2)) !== false) {
		$line = trim($line);
		$elements = explode(":", $line);

		file_put_contents($logfile, "command (" . $cmd . "), ele0 (" . $elements[0] . "), ele1 (" . $elements[1] . ")\n", FILE_APPEND);
		$finalcmd = search_terms($cmd, $elements[0], $elements[1]);
		if (!$finalcmd == "") break;
    }
	flock($fp2, LOCK_UN);
}
fclose($fp2);

if($finalcmd == "" && rand(1,100) > 80) {
	$num = rand(1,99);
	if ($num < 100) {
		$finalcmd = "Did he ask me something? I was in the other room";
	}
	if ($num < 80) {
		$finalcmd = "Are you talking about me?";
	}
	if ($num < 60) {
		$finalcmd = "If you keep saying my name, you'll wear it out!";
	}
	if ($num < 40) {
		$finalcmd = "I might start streaming, it looks easy!";
	}
	if ($num < 20) {
		$finalcmd = "I bought flowers for Nightbot <3";
	}
}

// process what commands we support and sent the bot instructions
if (!$finalcmd == "") {
	$fp = fopen("log/foo.txt", "a");
	if (flock($fp, LOCK_EX)) {
		fwrite($fp, strval(time()) . ":" . $sname . ":" . $finalcmd . "\n");
		flock($fp, LOCK_UN);
	}
	fclose($fp);
}

// So this searches for trigger words in the order they are given
function search_terms($cmd, $chatcmd, $trigger) {
	$triggers = explode(",", $trigger);
	$pos = 0;
	foreach ($triggers as $word) {
		$currentpos = strpos($cmd, $word);
		if ($currentpos === false || $currentpos < $pos) {
			return "";
		}
		$pos=$currentpos;
	}

	if (substr($chatcmd, 0 , 1) === '~') {
		// random line from file
		$file = substr($chatcmd, 1);
		$contents = file($file);
		$line = $contents[array_rand($contents)];
		$line = trim($line);
		$chatcmd = $line;
	}

	return $chatcmd;
}

?>
