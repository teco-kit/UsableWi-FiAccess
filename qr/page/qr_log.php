<?php
	require_once("auth.inc");
	require_once("functions.inc");
	require_once("captiveportal.inc");
	
	if($_POST['log'] == "true")
	{
		$tspb = time();
		$tspa = $tspb - ($_POST['tsp']/1000.0);
		$zeita = date("d.m.Y - H:i:s", $tspa);
		$zeitb = date("d.m.Y - H:i:s", $tspb);

		$logdatei = fopen("log.txt", "a");
		$logstring = $zeita.": qr {$_POST['logindex']} - first try\n";
		fwrite($logdatei, $logstring);
		if(local_backed($_POST['usr'], $_POST['pwd']))
		{
			$logstring = $zeitb.": qr {$_POST['logindex']} - access granted\n";
			fwrite($logdatei, $logstring);
			fclose($logdatei);
			
			// portal login
			$clientip = $_SERVER['REMOTE_ADDR'];
			$clientmac = arp_get_mac_by_ip($clientip);
			global $redirurl;
			if($_POST['logurl'] != "" && $_POST['logurl'] != "/index.php")
			{
				$redirurl = $_POST['logurl'];
			}
			else
			{
				$redirurl = "http://www.kit.edu";
			}
			portal_allow($clientip, $clientmac,"qr");
		}
		else
		{
			$logstring = $zeitb.": qr {$_POST['logindex']} - access denied\n";
			fwrite($logdatei, $logstring);
			fclose($logdatei);
			echo("<center>access denied</center>");
		}
	}
	else
	{
		echo("<center>try again</center>");
	}
?>
