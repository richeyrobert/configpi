#!/usr/bin/python
#
# We need some variables for later
host_name = ""
dhcp = ""
ip_addres = ""
subnet_mask = ""
gateway = ""

# This is the read file function
with open("/var/www/admin/configpi.config") as settings_from_web:
  for line in settings_from_web:
  	# Lets figure out what we are dealing with
  	if "HOSTNAME" in line:
  		host_name = line.split("=")[1]
  		print "This is the Host Name Line " + host_name
  	elif "DHCP" in line:
  		dhcp = line.split("=")[1]
  		print "This is the DHCP Line " + dhcp
  	elif "IPADDRESS" in line:
  		ip_addres = line.split("=")[1]
  		print "This is the IP Address Line " + ip_addres
  	elif "SUBNETMASK" in line:
  		subnet_mask = line.split("=")[1]
  		print "This is the Subnet Mask Line " + subnet_mask
  	elif "GATEWAY" in line:
  		gateway = line.split("=")[1]
  		print "This is the Gateway Line " + gateway
  	else:
  		print "We should never get here "+line

# This is the write file function
with open("/etc/network/interfaces", "a") as real_settings_file:
  real_settings_file.write('Hello\n')

# Sample interfaces file
# iface lo inet loopback
# iface eth0 inet static
# address 172.16.19.20
# netmask 255.255.254.0
# network 172.16.18.0
# broadcast 172.16.19.255
# gateway 172.16.18.1
# dns-nameservers 199.21.205.250 8.8.8.8


print "Content-Type: text/html\n\n"
print '<html><head><meta content="text/html; charset=UTF-8" />'
print '<title>ConfigPi Applying Settings</title><p>'
for count in range(1,100): 
  print 'Hello&nbsp;World... '
print "</p></body></html>"