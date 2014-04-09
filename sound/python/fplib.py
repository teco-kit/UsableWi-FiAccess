import numpy as np
import scipy as sp


# erstellt eine fingerprint liste mit (numtsparts-1)*(numfsparts-1) elementen
def fingerprint(data, numtsparts=17, numfsparts=33):
	endata = make_en(data, numtsparts, numfsparts)
	fpdata = make_fp(endata, numtsparts, numfsparts)
	return make_list(fpdata)


# gibt die hamming-distanz zweier fingerprints zurueck
def hamdist(data1, data2):
	dist = 0
	i = 0
	for x in data1:
		if x != data2[i]:
			dist += 1
		i+=1

	return dist


# fuehrt fft aus
def dofft(fdata):
	data = np.array(fdata)

	hanwin = np.hanning(data.size)
	data = hanwin*data
	
	data = abs(sp.fft(data))
	return data


# teilt das array in teilarrays auf und gibt diese zurueck
def splitarray(data, numparts):
	newsize = int(data.size) / int(numparts)
	fdata = np.zeros((numparts,newsize), dtype=np.float)

	for i in range(numparts):
		fdata[i] = data[i*newsize:(i+1)*newsize]

	return fdata


# integriert ueber eine datensequenz (hier vereinfacht als summe)
def integrate(data):
	val = 0
	for x in data:
		val += x
	
	return val


# berechnet die energie-matrix und gibt diese zurueck
def make_en(data, numtsparts, numfsparts):
	tsdata = splitarray(data, numtsparts)

	for i in range(numtsparts):
		tsdata[i] = dofft(tsdata[i])

	newsize = int(int(data.size) / int(numtsparts)) / int(numfsparts)
	fsdata = np.zeros((numtsparts,numfsparts, newsize), dtype=np.float)
	for i in range(numtsparts):
		fsdata[i] = splitarray(tsdata[i], numfsparts)

	endata = np.zeros((numtsparts, numfsparts), dtype=np.float)
	for i in range(numtsparts):
		for j in range(numfsparts):
			endata[i][j] = integrate(fsdata[i][j])

	return endata


# erstellt den fingerprint
def make_fp(data, numtsparts, numfsparts):
	fpdata = np.zeros((numtsparts-1, numfsparts-1), dtype=np.uint8)
	for i in range(numtsparts-1):
		for j in range(numfsparts-1):
			diffa = data[i][j] - data[i][j+1]
			diffb = data[i+1][j] - data[i+1][j+1]
			diffc = diffb - diffa
			
			if diffc > 0:
				fpdata[i][j] = 1

	return fpdata


# macht aus der fp-matrix eine fp-liste
def make_list(data):
	tmp = np.reshape(data, data.size)
	return tmp

