<?php

	function logging($opt)
	{
		$timestamp = time();
		$zeit = date("d.m.Y - H:i:s");
		$logdatei = fopen("log.txt", "a");
		fwrite($logdatei, $zeit.": ".$opt."\n");
		fclose($logdatei);
	}

?>
