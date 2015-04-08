#!/usr/bin/python
#
# We need to import some stuff
import ipaddr as ipaddress
import iptools
import re
# We need some variables for later
host_name = ""
dhcp = ""
ip_address = ""
subnet_mask = ""
gateway = ""
network = ""
broadcast = ""
dns_nameservers = "8.8.8.8"

# Output the main html crap for the page displayed...
print "Content-Type: text/html\n\n"
print '<html><head><meta content="text/html; charset=UTF-8" />'
print '<title>ConfigPi Applying Settings</title>'

# This is the read file function
with open("/var/www/admin/configpi.config") as settings_from_web:
  for line in settings_from_web:
  	# Lets figure out what we are dealing with
  	if "HOSTNAME" in line:
  		host_name = line.split("=")[1].strip()
  		print "This is the Host Name Line " + host_name + "<br>"
  	elif "DHCP" in line:
  		dhcp = line.split("=")[1].strip()
  		print "This is the DHCP Line " + dhcp + "<br>"
  	elif "IPADDRESS" in line:
  		ip_address = line.split("=")[1].strip()
  		print "This is the IP Address Line " + ip_address + "<br>"
  	elif "SUBNETMASK" in line:
  		subnet_mask = line.split("=")[1].strip()
  		print "This is the Subnet Mask Line " + subnet_mask + "<br>"
  	elif "GATEWAY" in line:
  		gateway = line.split("=")[1].strip()
  		print "This is the Gateway Line " + gateway + "<br>"
  	else:
  		print "We should never get here "+line + "<br>"
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

# Now let's set the host name
# We have to edit two separate files to do this:
# In /etc/hosts we need to edit the line '127.0.1.1     whatever' to '172.0.1.1     new hostname'
# In /etc/hostname we need to change the first line from 'old hostname to newhostname'

# Variable to hold the new string that we will write to the /etc/hosts file...
hosts_file_string = []

# Open the existing hosts file and read it into the hosts_file_string variable...
# Regex to find the host name in the /etc/hosts file = 127\.0\.1\.1[[:space:]]*\b[A-Z,a-z,0-9]\{1,62\}
with open("/etc/hosts", "r") as hosts_file:
  for line in hosts_file:
    if re.match("127\.0\.1\.1\s*[A-Z,a-z,0-9]{1,62}", line):
      # This is the line that we want to change...
      hosts_file_string.append("127.0.1.1   " + host_name)
    else:
      # Keep this line as it is and add it to the hosts_file_string variable...
      hosts_file_string.append(line)

# Now join all of the strings together...
file_string = "".join(hosts_file_string)

# Now let's write a replacement /etc/hosts file...
with open("/var/www/admin/host-config.txt", "w") as host_file:
  host_file.write(file_string + '\n')

# Now let's write a replacement /etc/hostname file...
with open("/var/www/admin/hostname-config.txt", "w") as hostname_file:
  hostname_file.write(host_name + '\n')

print '<meta http-equiv="refresh" content="2; url=http://' + ip_address +'" />'
print '</head>'
print '<h1>Please Wait... Redirecting browser.<h1><br>'
print "</p></body></html>"