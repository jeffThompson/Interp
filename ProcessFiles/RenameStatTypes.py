
out = []

with open('modelStats.csv') as file:
	for line in file:

		data = line.split(',')

		# extract types, set to full text format
		types = data[6].split('-')
		t = []
		for type in types:
			if type == 'b':
				t.append('blob')
			elif type == 'x':
				t.append('exploding')
			elif type == 'o':
				t.append('box')
			elif type == 'w':
				t.append('wing')
			elif type == 's':
				t.append('satellite')

		# rejoin as string with dashes between, add data into outpu
		data[6] = '-'.join(t)
		out.append(','.join(data))

with open('modelStats_typesListed.csv', 'w') as output:
	for line in out:
		output.write(line)
