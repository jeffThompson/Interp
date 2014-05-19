
import bpy 			# for blender operations
import os 			# for clear-screen, file listing
import glob

'''
DOWNSAMPLE OBJ
Jeff Thompson | 2014 | www.jeffreythompson.org


Built on this example structure:
http://auxmem.com/2012/01/24/convert-3ds-files-to-obj-with-blender

COMMAND:
-P 		Python script to use
-r 		silent output (not really, but quieter)
/Applications/Blender/blender.app/Contents/MacOS/blender -P DownsampleOBJ.py -r

'''


# variables
num_exported_faces = 5000 	# target number of faces
overwrite_file = True		# overwrite previous version - DANGER!

bold_start = '\033[1m'		# special characters for bold text output in Terminal window...
bold_end = '\033[0m'		# via http://askubuntu.com/a/45246


# clear screen
os.system('cls' if os.name=='nt' else 'clear')


# get list of files to process
files = glob.glob('*.obj')
num_files = len(files)

for i, file in enumerate(files):

	# extract file number, if not .obj file then skip
	filename, extension = os.path.splitext(file)
	if extension != '.obj':
		continue

	# create empty scene
	bpy.ops.object.select_all(action = 'SELECT')
	bpy.ops.object.delete()


	# read obj file
	print(bold_start + 'LOADING ' + str(i+1) + '/' + str(num_files) + bold_end)
	bpy.ops.import_scene.obj(filepath = str(filename) + '.obj')


	# get scene and object variables
	scene = bpy.context.scene
	object = bpy.ops.object


	# get vertex count, decimate
	# via: http://blenderscripting.blogspot.com/2011/05/blender-25-python-printing-vertex.html
	for item in bpy.data.objects:
		
		scene.objects.active = item

		vert_count = len(item.data.vertices)
		print('\n' + bold_start + '# VERTICES: ' + str(vert_count) + bold_end)

		if (vert_count > num_exported_faces):
			print('\n' + bold_start + 'DECIMATING MESH...' + bold_end)

			ratio = num_exported_faces / vert_count
			print('Ratio: ' + str(ratio))
			object.modifier_add(type = 'DECIMATE')
			for mod in item.modifiers:
				bpy.context.object.modifiers[mod.name].ratio = ratio
				object.modifier_apply(apply_as = 'DATA', modifier = 'Decimate')


	# write to new file
	print('\n' + bold_start + 'SAVING DECIMATED OBJ...' + bold_end)
	
	if overwrite_file:
		output = str(filename) + '.obj'
	else:
		output = str(filename) + '_decimated.obj'
	bpy.ops.export_scene.obj(filepath = output)

	# done!
	print('\n' + bold_start + 'SAVED!' + bold_end)
	print('\n' + ('- ' * 8))

