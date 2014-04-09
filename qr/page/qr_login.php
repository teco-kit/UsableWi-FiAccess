<?php

$logtsp = time();
$logzeit = date("d.m.Y - H:i:s", $logtsp);
$logindex = rand(0, 65535);
$logdatei = fopen("log.txt", "a");
$logstring = $logzeit . ": qr {$logindex} - captive portal\n";
fwrite($logdatei, $logstring);
fclose($logdatei);

include("captiveportal-qr_login.html");
echo("<script type='text/javascript'>document.logform.logindex.value = {$logindex};</script>");

?>
