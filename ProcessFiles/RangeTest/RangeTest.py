
import math

n = 10.2
print 'input: ', n

# 10k, 1k, 100, 10, 1
if math.floor(n/10000 % 1000):
	r = 10000
elif math.floor(n/1000 % 100):
	r = 1000
elif math.floor(n/100 % 1):
	r = 100
elif math.floor(n/10 % 1):
	r = 10
else:
	r = 1.0

# if larger than 1, round to integer
if n >= 1.0:
	n = int(n)

# DONE!
print 'output:', n, '-', n+r
print ''

