#!/bin/sh
pkill -KILL airodump-ng
rm /usr/local/captiveportal/powersheet-*

if ifconfig wlan5
then
	echo "warning: wlan5 device already created"
else
	ifconfig wlan5 create wlandev run0 wlanmode monitor
fi
if test $? -ne 0
then
	echo "error: cannot create wlandevice wlan5"
	return 1
fi

if test $# -eq 0
then
	echo "warning: scanning all channels"
	sleep 5
	airodump-ng --output-format csv --berlin 2 -w "/usr/local/captiveportal/powersheet" wlan5
else
	airodump-ng --output-format csv --berlin 2 --bssid $1 -w "/usr/local/captiveportal/powersheet" wlan5
fi
