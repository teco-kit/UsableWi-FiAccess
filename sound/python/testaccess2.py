import numpy as np
import fplib as fp


# Konstanten
CHECKRANGE = 500.0
STEPSIZE = 50.0
GRANTACCESS = 180


# prueft ob die audiofiles aehnlich genug sind
def testaccess(clientdata, serverdata, clientrate, serverrate):
	shift = CHECKRANGE
	while shift >= 0.0:
		servertop = int(serverdata.size - 1)
		serverbot = int(shift * serverrate)
		clienttop = int(clientdata.size - shift * clientrate - 1)
		clientbot = int(0)

		cd = clientdata[clientbot:clienttop]
		sd = serverdata[serverbot:servertop]

		fpclient = fp.fingerprint(cd)
		fpserver = fp.fingerprint(sd)

		dist = fp.hamdist(fpclient, fpserver)

		if dist <= GRANTACCESS:
			return True

		shift -= STEPSIZE

	return False

	
