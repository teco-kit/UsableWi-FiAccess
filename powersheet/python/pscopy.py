#!/usr/bin/python

import os
import sys

index = sys.argv[1]

datei = os.open("powersheet-"+index+".csv", os.O_RDONLY | os.O_NONBLOCK)
tmpdatei = open("pstemp.csv", "w")

while True:
	data = os.read(datei, 10);
	if data:
		tmpdatei.write(data)
	else:
		break

os.close(datei)
tmpdatei.flush()
tmpdatei.close()

