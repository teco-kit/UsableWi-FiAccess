<?php

$logtsp = time();
$logzeit = date("d.m.Y - H:i:s", $logtsp);
$logindex = rand(0, 65535);
$logdatei = fopen("log.txt", "a");
$logstring = $logzeit . ": sound {$logindex} - captive portal\n";
fwrite($logdatei, $logstring);
fclose($logdatei);

include("captiveportal-sound_login.html");
echo("<script type='text/javascript'>document.soundform1.logindex.value = {$logindex};</script>");
echo("<script type='text/javascript'>document.soundform2.logindex.value = {$logindex};</script>");

?>
