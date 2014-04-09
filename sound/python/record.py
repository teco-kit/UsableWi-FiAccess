import ossaudiodev as audio
import numpy as np
import time


# liest daten von einem audiogeraet und gibt die anzahl an gelesenen smaples zurueck
def sampleaudio(filename, seconds=2.0, devname="/dev/dsp", samplerate=44100, byteorder="little"):
	dev = audio.open(devname, 'r')

	try:
		from ossaudiodev import AFMT_S16_NE
	except:
		if byteorder == "big":
			AFMT_S16_NE = audio.AFMT_S16_BE
		else:
			AFMT_S16_NE = audio.AFMT_S16_LE
	
	fmt = dev.setfmt(AFMT_S16_NE)
	channels = dev.channels(1)
	rate = dev.speed(samplerate)

	timearray = []
	timearray.append(time.time() * 1000.0)
	bytestring = dev.read(int(seconds*rate*2))
	timearray.append(time.time() * 1000.0)
	timearray.append(rate/1000.0)

	dev.close()

	data = np.fromstring(bytestring, dtype=np.int16)
	np.savetxt(filename, data)

	return np.array(timearray, dtype=np.float)
	
