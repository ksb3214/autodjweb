<?php
require "twitch.php";

$debug = 0;
$names = [];
$coins = [];

$bots = array("lurxx","commanderroot","anotherttvviewer","alfredjudokus100289","aten","megaxa","winsock","streamlabs","v_and_k","streamelements","virgoproz","itsvodoo","skumshop","nightbot","soundwave87","feet","moobot","communityshowcase","justy_rl","bloodlustr","freast","msarianamarie","gzipped","twitchprimereminder","0x4c554b49","divarion","ssakdook","mentionsbot","vivbot","hunpippo1101","wizebot","mikuia","rutonybot","dataoctopus","cinfol","grizzilk","flytag_tv","maxwell_bo","artammahe","llabisa","zero3k","butexx","instructbot","aseyah","pocrevocrednu","liquigels","novusordobot","alfredhitchco");

if (islive("djunreal")) {
	$names = viewerlist("djunreal");
	//$names = viewerlist("ksbxx");
	if ($names === FALSE) {
		exit(0);
	}
	$names[] = "djunreal";
	shuffle($names);

	foreach($names as $name) {
		$ret = array_search($name, $bots);
		if ($ret != FALSE) {
			$myindex = array_search($name, $names);
			unset($names[$myindex]);
		}
	}

	print("Online\n");
}
else
{
	print("Offline\n");
}

if ((count($names) < 4) || $debug==1) {
	$names = array("testuser1", 
			"testuser2",
			"testuser3",
			"testuser4",
			"testuser5",
			"testuser6",
			"testuser7"
	);
}

$myfile = fopen("/var/www/html/autodjweb/log/users.txt", "w");
foreach($names as $name) {
	fwrite($myfile, $name . "\n");
}
fclose($myfile);



?>
