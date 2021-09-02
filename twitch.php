<?php
$clientid = 'as4ai7j5v1wxys7bud1mfrtbemmke7';
$streamid="156963559";

function getstreamername($id) {
	$fp2 = fopen("/opt/bot/ident.dat","r");
	if (flock($fp2, LOCK_EX)) {
	    while (($line = fgets($fp2)) !== false) {
	        $line = trim($line);
	        $elements = explode(":", $line);
			if ($elements[0] == $id) {
				return trim($elements[1]);
			}
	    }
	    flock($fp2, LOCK_UN);
	}
	fclose($fp2);
	return "";
}

function casterid($name) {
	global $clientid;
	$ch = curl_init("https://api.twitch.tv/helix/users?login=$name");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Client-ID: $clientid",
		"Authorizzation: Bearer $oauth"
	));
	$r = curl_exec($ch);
	curl_close($ch);

	$r2 = json_decode($r);

	$id = $r2->{'data'}['0']->{'id'};

#	print_r($r2);
	return $id;
}

function viewerlist($channel) {
	$ch = curl_init("https://tmi.twitch.tv/group/user/$channel/chatters");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$r = curl_exec($ch);
	curl_close($ch);

	if ($r === FALSE) {
		return FALSE;
	}

	$r2 = json_decode($r);
#	print_r($r2);
#	need to include moderators and vips and staff
	$ret = $r2->{'chatters'}->{'viewers'};
	$ret = array_merge($ret, $r2->{'chatters'}->{'vips'});
	$ret = array_merge($ret, $r2->{'chatters'}->{'moderators'});
	$ret = array_merge($ret, $r2->{'chatters'}->{'staff'});

	return $ret;
}

function islive($channel) {
	return True;
	global $clientid;
	$ch = curl_init("https://api.twitch.tv/helix/streams?user_login=$channel");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Client-ID: $clientid",
		"Authorizzation: $oauth"
	));
	$r = curl_exec($ch);
	curl_close($ch);

	$r2 = json_decode($r);
	print_r($r2);
	return True;

#	if (count($r2->{'data'}) > 0) {
#		return True;
#	} else {
#		return False;
#	}
}

# need to handle zero clips and errors
function gettopclip($id) {
	global $clientid;
	$sdate = new DateTime('-4 week');
	$sdate = urlencode($sdate->format(\DateTime::RFC3339));
	$edate = new DateTime('now');
	$edate = urlencode($edate->format(\DateTime::RFC3339));
	$ch = curl_init("https://api.twitch.tv/helix/clips?broadcaster_id=$id&started_at=$sdate&ended_at=$edate");
#	$ch = curl_init("https://api.twitch.tv/helix/clips?broadcaster_id=$id");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Client-ID: $clientid"
	));
	$r = curl_exec($ch);
	curl_close($ch);

	$r2 = json_decode($r);
	#print_r($r2);
	$topclip = $r2->{'data'}['0']->{'id'};
	return $topclip;
}

# need to handle zero clips and errors
function getlatestvod($id) {
	global $clientid;
	$sdate = new DateTime('-4 week');
	$sdate = urlencode($sdate->format(\DateTime::RFC3339));
	$edate = new DateTime('now');
	$edate = urlencode($edate->format(\DateTime::RFC3339));
	$ch = curl_init("https://api.twitch.tv/helix/videos?user_id=$id");
#	$ch = curl_init("https://api.twitch.tv/helix/clips?broadcaster_id=$id");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Client-ID: $clientid"
	));
	$r = curl_exec($ch);
	curl_close($ch);

	$r2 = json_decode($r);
#	print_r($r2);
	$topvod = $r2->{'data'}['0']->{'id'};
	return $topvod;
}

#$id = casterid("federalghosts");
#$ret = gettopclip($id);

#getclip();
#$ret = getlatestvod($id);
islive("djunreal");
?>
