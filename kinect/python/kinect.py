import httplib
import time

SLEEPTIME = 1.0
logdatei = open("kinect.log", "a")
logdatei.write("waiting for server response\n")

try:
	ipdatei = open("ip.txt", "r")
	serverip = ipdatei.readline().strip('\n')
	serverport = int(ipdatei.readline().strip('\n'))
	ipdatei.close()
except:
	print "error: cannot read server ip"
	logdatei.write("error: cannot read server ip\n")
	logdatei.close()
	exit(1)
	
try:
	con = httplib.HTTPConnection(serverip, serverport)
	con.request("GET", "/?dev=server&cmd=addUser")
	res = con.getresponse()
	if res.status != 200:
		raise httplib.HTTPException
except:
	print "error: cannot connect to server"
	logdatei.write("error: cannot connect to server\n")
	logdatei.close()
	exit(2)
	
try:
	data = res.read()
	if data != "True" and data != "true":
		raise httplib.HTTPException
	
	handsup = False
	for x in range(5):
		con.request("GET", "/?dev=server&cmd=activateGestureCtrl")
		res = con.getresponse()
		data = res.read()
		if data == "True" or data == "true":
			handsup = True
			break
		time.sleep(SLEEPTIME)
	
	con.request("GET", "/?dev=server&cmd=close")
	res = con.getresponse()
	data = res.read()
	con.close()
except:
	print "error: authentication failed"
	logdatei.write("error: authentication failed\n")
	logdatei.close()
	exit(3)

if handsup:
	logdatei.write("access granted\n")
else:
	logdatei.write("access denied\n")

logdatei.close()
exit(0)