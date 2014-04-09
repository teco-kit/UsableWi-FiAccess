<?php
	if($_GET['nfc'] == "true")
	{
		require_once("auth.inc");
		require_once("functions.inc");
		require_once("captiveportal.inc");
		
		// portal login
		$clientip = $_SERVER['REMOTE_ADDR'];
		$clientmac = arp_get_mac_by_ip($clientip);
		global $redirurl;
		$tempurl = explode("redirurl=", $_GET['redirurl']);
		if($tempurl[1] != "" && $tempurl[1] != "/index.php")
		{
			$redirurl = $tempurl[1];
		}
		else
		{
			$redirurl = "http://www.google.de";
		}

		if(local_backed($_GET['usr'], $_GET['pwd']))
		{
			// logging
			require_once("captiveportal-logging.php");
			$logstring = "nfc tag - access granted";
			logging($logstring);
			
			portal_allow($clientip, $clientmac,"nfc");
		}
		else
		{
			// logging
			require_once("captiveportal-logging.php");
			$logstring = "nfc tag - access denied";
			logging($logstring);

			include("captiveportal-nfc_login.html");
			echo("<center>NFC login rejected. access denied</center>");
		}
	}
	else
	{
		$logtsp = time();
		$logzeit = date("d.m.Y - H:i:s", $logtsp);
		$logindex = rand(0, 65535);
		$logdatei = fopen("log.txt", "a");
		$logstring = $logzeit . ": nfc {$logindex} - captive portal\n";
		fwrite($logdatei, $logstring);
		fclose($logdatei);

		include("captiveportal-nfc_login.html");
	}
?>
