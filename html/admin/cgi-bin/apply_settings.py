#!/usr/bin/python
#
# We need to import some stuff
import ipaddr as ipaddress
import iptools
# We need some variables for later
host_name = ""
dhcp = ""
ip_address = ""
subnet_mask = ""
gateway = ""
network = ""
broadcast = ""
dns_nameservers = "8.8.8.8"

# This is the read file function
with open("/var/www/admin/configpi.config") as settings_from_web:
  for line in settings_from_web:
  	# Lets figure out what we are dealing with
  	if "HOSTNAME" in line:
  		host_name = line.split("=")[1].strip()
  		print "This is the Host Name Line " + host_name
  	elif "DHCP" in line:
  		dhcp = line.split("=")[1].strip()
  		print "This is the DHCP Line " + dhcp
  	elif "IPADDRESS" in line:
  		ip_address = line.split("=")[1].strip()
  		print "This is the IP Address Line " + ip_address
  	elif "SUBNETMASK" in line:
  		subnet_mask = line.split("=")[1].strip()
  		print "This is the Subnet Mask Line " + subnet_mask
  	elif "GATEWAY" in line:
  		gateway = line.split("=")[1].strip()
  		print "This is the Gateway Line " + gateway
  	else:
  		print "We should never get here "+line
# Let's try to get some other information from the IP Address
net_info = iptools.ipv4.subnet2block(ip_address + '/' + subnet_mask)
network = net_info[0].strip()
broadcast = net_info[1].strip()

# This is the write file function
with open("/var/www/admin/ip-config.txt", "w") as real_settings_file:
  real_settings_file.write('# Automatically generated ethernet settings.\n')
  real_settings_file.write('iface lo inet loopback\n')
  if "NO" in dhcp:
  	real_settings_file.write('iface eth0 inet static\n')
  	real_settings_file.write('address ' + ip_address  + '\n')
  	real_settings_file.write('netmask ' + subnet_mask + '\n')
  	real_settings_file.write('network ' + network + '\n')
  	real_settings_file.write('broadcast ' + broadcast + '\n')
  	real_settings_file.write('gateway ' + gateway + '\n')
  	real_settings_file.write('dns-nameservers ' + dns_nameservers + '\n')

  elif "YES" in dhcp:
  	real_settings_file.write('iface eth0 inet dhcp\n')
  else:
  	# We should never reach this point
  	print "We should never reach this point."
	# Sample interfaces file
	# iface lo inet loopback
	# iface eth0 inet static
	# address 172.16.19.20
	# netmask 255.255.254.0
	# network 172.16.18.0
	# broadcast 172.16.19.255
	# gateway 172.16.18.1
	# dns-nameservers 199.21.205.250 8.8.8.8
with open("/var/www/admin/host-config.txt", "w") as real_settings_file:
  real_settings_file.write('# Automatically generated ethernet settings.\n')


print "Content-Type: text/html\n\n"
print '<html><head><meta content="text/html; charset=UTF-8" />'
print '<title>ConfigPi Applying Settings</title><p>'
for count in range(1,100): 
  print 'Hello&nbsp;World... '
print "</p></body></html>"