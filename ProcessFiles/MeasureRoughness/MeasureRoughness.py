
import re, math



obj_filename = '096.obj'

vertices = []
normals = []

def dotproduct(v1, v2):
	return sum((a*b) for a, b in zip(v1, v2))

def length(v):
	return math.sqrt(dotproduct(v, v))

def angle(v1, v2):
	return math.acos(dotproduct(v1, v2) / (length(v1) * length(v2)))

# load all vertices, normals
with open(obj_filename) as file:
	for line in file:
		l = line.split()
		if len(l) == 4 and l[0] == 'v':
			vertices.append([ float(l[1]), float(l[2]), float(l[3]) ])
		elif len(l) == 4 and l[0] == 'vn':
			normal = [ float(l[1]), float(l[2]), float(l[3]) ]
			normals.append(normal)

print '# vertices:', len(vertices)
print '# normals:', len(normals)

for n in normals:
	a = angle(n, [0,0,0])




