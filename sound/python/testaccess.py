import numpy as np
import fplib as fp


# Konstanten
CHECKRANGE = 1000.0
STEPSIZE = 250.0
FINDDIRSTEP = 100.0
LOOKUPSTEP = 50.0
THRESHOLD = 220
GRANTACCESS = 180


# prueft ob die audiofiles aehnlich genug sind
def testaccess(clientdata, serverdata, clientrate, serverrate):
	isbadpos, pos1, dist1 = getfirstpos(clientdata, serverdata, clientrate, serverrate)
	
	if isbadpos:
		return False

	if dist1 <= GRANTACCESS:
		return True

	mul, pos2, dist2 = finddirection(pos1, clientdata, serverdata, clientrate, serverrate)

	if dist2 <= GRANTACCESS:
		return True

	access = checkpos(pos2, clientdata, serverdata, clientrate, serverrate)

	if access:
		return True

	access = checkpos(pos2 + mul*FINDDIRSTEP, clientdata, serverdata, clientrate, serverrate)

	if access:
		return True
	
	return False


# gibt die erst beste position unterhalb von THRESHOLD zurueck
def getfirstpos(clientdata, serverdata, clientrate, serverrate):
	startshift = -(CHECKRANGE/2.0)
	numshifts = int(CHECKRANGE/STEPSIZE) + 1

	for i in range(numshifts):
		shift = startshift + i*STEPSIZE

		clienttop = 0
		clientbot = 0
		servertop = 0
		serverbot = 0

		if shift >= 0.0:
			servertop = int(serverdata.size - shift * serverrate - 1)
			serverbot = int(0)
			clienttop = int(clientdata.size - 1)
			clientbot = int(shift * clientrate)
			
		if shift < 0.0:
			servertop = int(serverdata.size - 1)
			serverbot = int(-1 * shift * serverrate)
			clienttop = int(clientdata.size + shift * clientrate - 1)
			clientbot = int(0)

		cd = clientdata[clientbot:clienttop]
		sd = serverdata[serverbot:servertop]

		fpclient = fp.fingerprint(cd)
		fpserver = fp.fingerprint(sd)

		dist = fp.hamdist(fpclient, fpserver)

		if dist <= THRESHOLD:
			return False, shift, dist

	return True, 0.0, 0


# findet die richtung in der das minimum liegt
def finddirection(pos, clientdata, serverdata, clientrate, serverrate):
	shift1 = pos - FINDDIRSTEP
	shift2 = pos + FINDDIRSTEP

	# shift1
	clienttop = 0
	clientbot = 0
	servertop = 0
	serverbot = 0

	if shift1 >= 0.0:
		servertop = int(serverdata.size - shift1 * serverrate - 1)
		serverbot = int(0)
		clienttop = int(clientdata.size - 1)
		clientbot = int(shift1 * clientrate)
		
	if shift1 < 0.0:
		servertop = int(serverdata.size - 1)
		serverbot = int(-1 * shift1 * serverrate)
		clienttop = int(clientdata.size + shift1 * clientrate - 1)
		clientbot = int(0)

	cd1 = clientdata[clientbot:clienttop]
	sd1 = serverdata[serverbot:servertop]

	fpclient1 = fp.fingerprint(cd1)
	fpserver1 = fp.fingerprint(sd1)

	dist1 = fp.hamdist(fpclient1, fpserver1)

	# shift2
	clienttop = 0
	clientbot = 0
	servertop = 0
	serverbot = 0
	
	if shift2 >= 0.0:
		servertop = int(serverdata.size - shift2 * serverrate - 1)
		serverbot = int(0)
		clienttop = int(clientdata.size - 1)
		clientbot = int(shift2 * clientrate)
		
	if shift2 < 0.0:
		servertop = int(serverdata.size - 1)
		serverbot = int(-1 * shift2 * serverrate)
		clienttop = int(clientdata.size + shift2 * clientrate - 1)
		clientbot = int(0)

	cd2 = clientdata[clientbot:clienttop]
	sd2 = serverdata[serverbot:servertop]

	fpclient2 = fp.fingerprint(cd2)
	fpserver2 = fp.fingerprint(sd2)

	dist2 = fp.hamdist(fpclient2, fpserver2)

	# richtung auswaehlen
	if dist2 <= dist1:
		return 1.0, shift2, dist2
	else:
		return -1.0, shift1, dist1


# ueberprueft einen bereich links und rechts einer position
def checkpos(pos, clientdata, serverdata, clientrate, serverrate):
	shift1 = pos - LOOKUPSTEP
	shift2 = pos + LOOKUPSTEP

	clienttop = 0
	clientbot = 0
	servertop = 0
	serverbot = 0

	# shift1
	if shift1 >= 0.0:
		servertop = int(serverdata.size - shift1 * serverrate - 1)
		serverbot = int(0)
		clienttop = int(clientdata.size - 1)
		clientbot = int(shift1 * clientrate)
		
	if shift1 < 0.0:
		servertop = int(serverdata.size - 1)
		serverbot = int(-1 * shift1 * serverrate)
		clienttop = int(clientdata.size + shift1 * clientrate - 1)
		clientbot = int(0)

	cd1 = clientdata[clientbot:clienttop]
	sd1 = serverdata[serverbot:servertop]

	fpclient1 = fp.fingerprint(cd1)
	fpserver1 = fp.fingerprint(sd1)

	dist1 = fp.hamdist(fpclient1, fpserver1)

	if dist1 <= GRANTACCESS:
		return True

	# shift2
	if shift2 >= 0.0:
		servertop = int(serverdata.size - shift2 * serverrate - 1)
		serverbot = int(0)
		clienttop = int(clientdata.size - 1)
		clientbot = int(shift2 * clientrate)
		
	if shift2 < 0.0:
		servertop = int(serverdata.size - 1)
		serverbot = int(-1 * shift2 * serverrate)
		clienttop = int(clientdata.size + shift2 * clientrate - 1)
		clientbot = int(0)

	cd2 = clientdata[clientbot:clienttop]
	sd2 = serverdata[serverbot:servertop]

	fpclient2 = fp.fingerprint(cd2)
	fpserver2 = fp.fingerprint(sd2)

	dist2 = fp.hamdist(fpclient2, fpserver2)

	if dist2 <= GRANTACCESS:
		return True

	return False
	
