
'''
BATCH RENDER THUMBNAILS
Jeff Thompson | 2014 | www.jeffreythompson.org

TO DO:
+ find extents based on camera FOV and distance to origin?
	- maybe using http://www.extentofthejam.com/pseudo
+ render high res, build smart cropper in P5 that trims to edge of model

COMMAND:
1. 	unset PYTHONPATH
2. 	/Applications/Blender/blender.app/Contents/MacOS/blender -P BatchRenderThumbnails.py -r

'''

import bpy			# for Blender commands
import glob			# for listing obj files
import os 			# for clear-screen
import shutil 		# for moving files when rendered
import math


output_folder = 'thumbnails'			# folder to write thumbnail files to
width = 1920 * 3						# resolution of rendered image in pixels (high and then cropped later)
height = 1080 * 3
background_color = (0.8, 0.8, 0.8)		# 204/255
extents = 3.0 							# found through trial and error
light_brightness = 0.15					# brightness of lighting (0-1)
move_rendered_models = False			# move model files when done

bold_start = '\033[1m'								# special characters for bold text output in Terminal window...
bold_end = '\033[0m'								# via http://askubuntu.com/a/45246

# cleanup and necessary business
os.system('cls' if os.name=='nt' else 'clear')		# clear screen

# get list of all obj files 
obj_files = glob.glob('*.obj')

# create empty scene
bpy.ops.object.select_by_type(type = 'MESH')		# plain-old select all deletes camera too!
bpy.ops.object.delete()

# add area light at the camera's position
cam_pos = bpy.data.objects['Camera'].location
bpy.ops.object.lamp_add(type='AREA', view_align=False, location=cam_pos, layers=(True, False, False, False, False, False, False, False, False, False, False, False, False, False, False, False, False, False, False, False))
bpy.context.object.data.shadow_method = 'RAY_SHADOW'
bpy.context.object.data.energy = light_brightness

# enumerate and export rendered image
for i, filename in enumerate(obj_files):
	
	print(bold_start + 'LOADING FILE ' + str(i+1) + '/' + str(len(obj_files)) + bold_end)

	# read obj file
	bpy.ops.import_scene.obj(filepath = filename)
	obj = bpy.data.objects[filename[0:-4]]

	# get object dimensions, scale to fit camera
	print('\n' + bold_start + 'MODEL STATS' + bold_end)
	dims = obj.dimensions
	print('dimensions: ', dims)
	proportion = extents / max(dims) 							# extents in each direction +/-
	print('proportion: ', proportion)
	obj.scale = (proportion, proportion, proportion)			# scale to fit

	bpy.ops.object.origin_set(type='ORIGIN_CENTER_OF_MASS')		# move to origin

	# render settings
	scene = bpy.context.scene
	scene.render.resolution_x = width * 2 			# a hack, necessary for some reason :(
	scene.render.resolution_y = height * 2
	# scene.world.horizon_color = background_color
	scene.render.alpha_mode = 'TRANSPARENT'

	# create output filename, render
	output_filename = output_folder + '/' + filename[0:-4] + '.png'
	print('\n' + bold_start + 'WRITING "' + output_filename + '"' + bold_end)
	
	bpy.data.scenes['Scene'].render.filepath = output_filename
	bpy.ops.render.render(write_still = True)

	# delete object from scene
	bpy.data.objects[filename[0:-4]].select = True
	bpy.ops.object.delete()

	# move rendered files
	if move_rendered_models:
		shutil.move(filename, 'rendered/' + filename)
		shutil.move(filename[0:-4] + '.mtl', 'rendered/' + filename[0:-4] + '.mtl')
		shutil.move(filename[0:-4] + '.jpg', 'rendered/' + filename[0:-4] + '.jpg')

	print(('- ' * 8) + '\n')

# all done!
print(bold_start + 'ALL DONE!' + bold_end)

