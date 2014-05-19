
'''
RENAME OBJ/MTL TEXTURE
Jeff Thompson | 2014 | www.jeffreythompson.org

'''

import glob			# for listing files
import fileinput	# for editing file

# get all mtl files in directory
mtl_files = glob.glob('*.mtl')

# iterate mtl files, replace lines in file
for i, mtl in enumerate(mtl_files):
	print str(i+1) + '/' + str(len(mtl_files)) + '...'
	for line in fileinput.input(mtl, inplace = True):
			if len(line.strip()) == 0 or line[0] == '#':	# skip blank lines and comments
				pass
			elif 'map_Kd' in line:							# replace png with jpg
				print line.replace('.png', '.jpg'),
			else:											# otherwise, write line as is
				print line,

# done!
print '\n' + 'DONE!' + '\n\n'