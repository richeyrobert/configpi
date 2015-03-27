#!/usr/bin/python
#
with open("/var/www/admin/configpi.config") as f:
  for line in f:
    print line

print "Content-Type: text/html\n\n"
print '<html><head><meta content="text/html; charset=UTF-8" />'
print '<title>ConfigPi Applying Settings</title><p>'
for count in range(1,100): 
  print 'Hello&nbsp;World... '
print "</p></body></html>"