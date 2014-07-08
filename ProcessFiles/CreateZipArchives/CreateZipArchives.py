
import glob, zipfile, shutil, os

'''
CREATE ZIP ARCHIVES
Jeff Thompson | 2014 | www.jeffreythompson.org

A lazy little script to create zip archives of the models.

'''

# get all obj files in the directory
obj_files = glob.glob('*.obj')

# go through one-by-one...
for obj in obj_files:
	num = obj[-7:-4]
	print num

	# copy thumbnail file to directory, rename
	shutil.copyfile('../thumbnails/' + num + '.png', 'thumbnail.png')

	# create list of files to add to zip
	files = [ num + '.obj', num + '.mtl', num + '.jpg', 'thumbnail.png', 'readme.txt' ]

	# create zip!
	print '  writing zip archive...'
	z = zipfile.ZipFile('model-' + num + '.zip', 'w')
	for f in files:
		z.write(f)
	z.close()
	print '  successful!' + '\n'

	# delete thumbnail file
	os.remove('thumbnail.png')

# done!
print 'DONE!'
