<?php

include("captiveportal-sound_login.html");


$index = 1;

// ermittelt den session index und reserviert diesen
$index_data = file("session_index.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach($index_data as $string)
{
	if($index != $string)
		break;

	$index++;
}

if($index > 256)
{
	echo("<center>Server ist zur Zeit ueberlastet ... versuchen Sie es spaeter nocheinmal</center>");
}
else
{

	$index_datei = fopen("session_index.txt", "w");
	$i = 1;
	$notwritten = true;
	foreach($index_data as $string)
	{
		if($i == $index)
		{
			fwrite($index_datei, $index."\n");
			$notwritten = false;
		}
		fwrite($index_datei, $string."\n");
		$i++;
	}

	if($notwritten)
		fwrite($index_datei, $index."\n");
	
	fclose($index_datei);
	
	echo("<center>recording ... please wait</center>");
	
	// result auf recording setzen
	$result_datei = fopen("{$index}_result.txt", "w");
	fwrite($result_datei, "recording");
	fclose($result_datei);

	echo("<script type='text/javascript'>document.soundform1.index_data.value = {$index};</script>");
	echo("<script type='text/javascript'>document.soundform1.logindex.value = {$_POST['logindex']};</script>");
	echo("<script type='text/javascript'>document.soundform2.logindex.value = {$_POST['logindex']};</script>");
	echo("<script type='text/javascript'>document.soundform2.recordurl.value = '{$_POST['recordurl']}';</script>");
	echo("<script type='text/javascript'>window.onload=pageLoad;</script>");

	// logging
	require_once("captiveportal-logging.php");
	$logstring = "sound {$_POST['logindex']} - record start - index: {$index}";
	logging($logstring);

	// python-record starten
	exec("python main.py record {$index} > /dev/null &");
}

?>
