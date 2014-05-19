
'''
FIX OBJ FILES
Jeff Thompson | 2014 | www.jeffreythompson.org

'''

import glob			# for listing files
import fileinput	# for editing file

# get all obj files in directory
obj_files = glob.glob('*.obj')

# iterate obj files, replace lines in file
for i, obj in enumerate(obj_files):
	print str(i+1) + '/' + str(len(obj_files)) + '...'
	for line in fileinput.input(obj, inplace = True):
			if len(line.strip()) == 0 or line[0] == '#':	# skip blank lines and comments
				pass
			elif 'mtllib' in line:							# add new line below
				print line + '\n' + 'usemtl Textured',
			else:											# otherwise, write line as is
				print line,

# done!
print '\n' + 'DONE!' + '\n\n'