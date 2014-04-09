import numpy as np
import json


def getdata(filename):
	datei = open(filename, "r")

	string = datei.readline()
	data = json.loads(string)

	datei.close()

	return data
