<?php
/**
 * WebNotifier
 * (c) Tommi Korhonen 
 * tok (at) iki . fi
 */

define('DEFAULT_PROPERTIES_FILE','webnotify.properties');
define('MAILSENT_FILE_EXTENSION','MAILSENT');
define('KEY_URL','url');
define('KEY_KEY','key');
define('KEY_MATCH','match');
define('KEY_MAIL','mail');
define('KEY_COOKIE','cookie');

function sendmail($to,$msg,$mailsent_file) { 
	if (mail($to,'Notification from WebNotify',$msg.PHP_EOL)) {
		$fp = fopen($mailsent_file,"w");
		fwrite($fp,$msg);
		fclose($fp);
		print "Mail sent to $to".PHP_EOL;
	} else {
		print "Mail failed".PHP_EOL;
	}
}

// http://stackoverflow.com/questions/1975461/how-to-get-file-get-contents-work-with-https
function getSslPage($url,$cookie) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	if ($cookie != null) { 
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	}
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

$properties_file = isset($argv[1]) ? $argv[1] : DEFAULT_PROPERTIES_FILE;
if (!file_exists($properties_file)) {
	exit("File $properties_file not found in current directory");
}
$mailsent_file = $properties_file . '.' . MAILSENT_FILE_EXTENSION;
if (file_exists($mailsent_file)) exit;

$propfile = file($properties_file);
$properties = array();
foreach($propfile as $line) {
	list($key,$value) = explode('=',trim($line),2);
	$properties[$key] = $value;
}
$properties_keys = array(KEY_URL,KEY_KEY,KEY_MATCH,KEY_MAIL);
foreach($properties_keys as $key) {
	if (!array_key_exists($key,$properties)) {
		exit("No $key in $properties_file file");
	}
}

$url = $properties[KEY_URL];
$cookie = isset($properties[KEY_COOKIE]) ? $properties[KEY_COOKIE] : null; 
if (preg_match("/^https:/",$url)) {
	$html = getSslPage($url,$cookie);
} else {
	$html = file_get_contents($url);
}
if ($html !== false && $html != '') {
	$key = $properties[KEY_KEY];
	if (preg_match("/$key/",$html,$matches)) {		
		if (strtoupper($properties[KEY_MATCH]) == 'TRUE') {			
			$msg = "Regex /$key/ (match=".$matches[0].")  found in $url";
			print $msg.PHP_EOL; 
			sendmail($properties[KEY_MAIL],$msg,$mailsent_file);			
		}
	} else {
		if (strtoupper($properties[KEY_MATCH]) == 'FALSE') {
			$msg = "Regex '$key' not found in $url"; 
			print $msg.PHP_EOL; 
			sendmail($properties[KEY_MAIL],$msg,$mailsent_file);
		}		
	}	
}
