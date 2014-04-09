import sys
import os

switcher = sys.argv[1]
index = sys.argv[2]

try:
	from record import sampleaudio
	from jsondata import getdata
	from testaccess import testaccess
	import numpy as np

	RECORD_TIME = 8.0
	RECORD_DEVICE = "/dev/dsp6"
except:
	print "import error"
	datei = open(index + "_result.txt", "w")
	datei.write("import error")
	datei.flush()
	os.fsync(datei)
	datei.close()
	os._exit(0)
	

if switcher == "record":
	try:
		timearray = sampleaudio(index + "_serverdata.txt", RECORD_TIME, RECORD_DEVICE)
	except:
		print "sound error"
		datei = open(index + "_result.txt", "w")
		datei.write("sound error")
		datei.flush()
		os.fsync(datei)
		datei.close()
		os._exit(0)
	
	np.savetxt(index + "_servertime.txt", timearray)

	print "record done"
	datei = open(index + "_result.txt", "w")
	datei.write("record done")
	datei.flush()
	os.fsync(datei)
	datei.close()
	os._exit(0)


if switcher == "check":
	try:
		clientarray = getdata(index + "_clientdata.txt")
		clientdata = np.array(clientarray[0], dtype = np.float)
		clienttime = np.array(clientarray[1], dtype = np.float)
		serverdata = np.loadtxt(index + "_serverdata.txt", dtype = np.float)
		servertime = np.loadtxt(index + "_servertime.txt", dtype = np.float)
	except:
		print "file error"
		datei = open(index + "_result.txt", "w")
		datei.write("file error")
		datei.flush()
		os.fsync(datei)
		datei.close()
		os._exit(0)
	

	clientrate = clienttime[2]
	serverrate = servertime[2]

	try:
		if testaccess(clientdata, serverdata, clientrate, serverrate):
			print "access granted"
			datei = open(index + "_result.txt", "w")
			datei.write("access granted")
			datei.flush()
			os.fsync(datei)
			datei.close()
		else:
			print "access denied"
			datei = open(index + "_result.txt", "w")
			datei.write("access denied")
			datei.flush()
			os.fsync(datei)
			datei.close()
			os._exit(0)

	except:
		print "testaccess error"
		datei = open(index + "_result.txt", "w")
		datei.write("testaccess error")
		datei.flush()
		os.fsync(datei)
		datei.close()
		os._exit(0)

	os._exit(0)
