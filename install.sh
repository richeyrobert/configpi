#!/bin/bash
# 
#
# *********************************
# **** ConfigPi install script ****
# *********************************
# 1. Make sure that the script is being run by a root user
# 2. Make sure that the proper packages are installed
# 3. Move all of the scripts and the web pages into the correct locations
# 4. Make sure all of the scripts are executable
# 5. Make sure all of the files have the proper permissions
# 6. Make the necessary changes to any config files
# 7. Make the default usernames and passwords for the web interface
# 8. Restart all of the necessary services
#
# *********************************
# ***** Important Information *****
# *********************************
# The location for the CGI scripts for lighty:
#	/usr/lib/cgi-bin
# Lighty Log files are located at:
#	/var/log/lighttpd
#
# 1. Make sure that the script is being run by a root user
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root" 1>&2
   exit 1
fi
#
# 2. Make sure that the proper packages are installed
echo "Making sure that the proper packages are installed..."
apt-get install -y lighttpd php5-cgi python-ipaddr python-pip #< Insert the proper packages here >
# Need to instal a python package
pip install iptools
# Enable the CGI module for lighty
lighty-enable-mod cgi
lighty-enable-mod fastcgi 
lighty-enable-mod fastcgi-php
lighty-enable-mod auth

# Force a lighty reload to enable the changes
service lighttpd force-reload
#
# Configure the authentication service config files...
echo "Configuring the authentication system..."
# Rename the current file to backup 
mv /etc/lighttpd/conf-enabled/05-auth.conf /etc/lighttpd/conf-enabled/05-auth.conf.backup
# Move the sample file to the actual config file
mv config_files/05-auth.sample /etc/lighttpd/conf-enabled/05-auth.conf
# Now change the permissions and ownership of the file
chmod 644 /etc/lighttpd/conf-enabled/05-auth.conf
chown root:root /etc/lighttpd/conf-enabled/05-auth.conf
#
# Force another lighty reload
service lighttpd force-reload
# 3. Make the default usernames and passwords for the web interface
# Prompt user for username and password and password confirmation...
echo "Creating the default usernames and passwords..."
# Now add the username and passwords
# Get the desired password from the user...
input_password="fart"
verify_password="head"
while [  "$input_password" != "$verify_password" ]; do
	echo "Please enter the desired admin password: "
	read -s input_password
	# Verify the password
	echo "Verify password: "
	read -s verify_password
	if [  "$input_password" != "$verify_password" ]; then
		echo "Passwords do not match!"
		echo "Please try again."
	fi
done
# Now we need to hash the password and write it to the /etc/lighttpd/lighttpd.user file
# The hash needs to be saved to the file as "$user:$realm:$password"
combined_password="admin:Admin Realm:$input_password"
# Now we need to Hash the password
md5hash=`printf '%s' "$combined_password"|md5sum|cut -d" " -f1`
# Now move the password to the user key file...
touch /etc/lighttpd/lighttpd.user
echo "admin:Admin Realm:$md5hash" >> /etc/lighttpd/lighttpd.user
# 4. Move all of the scripts and the web pages into the correct locations
echo "Making sure all the files are in the proper locations..."
# Lets move the html first...
cp -R html/* /var/www/
cp config_files/settings_applier.sh /usr/local/bin/settings_applier
#
# Move the settings_applier script to the proper location

# 5. Make sure all of the scripts are executable
echo "Making sure all proper scripts are executable..."
chmod 755 /var/www/admin/cgi-bin/apply_settings.py
# Have to set sticky bit here because changing owner resets the sticky bit...
chown root:staff /usr/local/bin/settings_applier
chmod 4755 /usr/local/bin/settings_applier
#
# 6. Make sure all of the files have the proper permissions
echo "Making sure all of the files have the proper permissions..."
chown www-data:www-data /var/www/admin/configpi.config
chown www-data:www-data /var/www/admin/ip-config.txt
chown www-data:www-data /var/www/admin/host-config.txt
chown www-data:www-data /var/www/admin/hostname-config.txt
chown www-data:www-data /var/www/admin/cgi-bin/apply_settings.py
#
# 7. Make the necessary changes to any config files
echo "Making the necessary changes to any config files..."
# Change the line: $HTTP["url"] =~ "^/cgi-bin/" { 
# to $HTTP["url"] =~ "^/admin/cgi-bin/" { 
# in the file /etc/lighttpd/conf-enabled/10-cgi.conf 
echo "Applying settings to the lighty config files..."
sed -i '
/\$HTTP\["url"] =~ "\^\/cgi-bin\/" {/ c\
$HTTP["url"] =~ "^/admin/cgi-bin/" {' /etc/lighttpd/conf-enabled/10-cgi.conf
# Change the line:        cgi.assign = ( "" => "" )
# in the file /etc/lighttpd/conf-enabled/10-cgi.conf 
# to:        cgi.assign = ( ".py" => "/usr/bin/python" )
sed -i '
/[[:space:]]cgi\.assign\s\=\s.\s\"\"\s\=>\s\"\"\s./ c\
        cgi.assign = ( ".py" => "/usr/bin/python" )' /etc/lighttpd/conf-enabled/10-cgi.conf
#
# Now add the necessary changes to the visudo file to allow the script to run as root.
# Create the included visudo file
touch /etc/sudoers.d/www-data
# Write changes to the file
echo "www-data ALL=NOPASSWD: /usr/local/bin/settings_applier" >> /etc/sudoers.d/www-data
# Change the ownership and the permissions of the file
chmod 0440 /etc/sudoers.d/www-data
chown root:root /etc/sudoers.d/www-data
# Now the settings_applier will be able to be called from the www-data user without needing a sudo password.

# 8. Restart all of the necessary services
echo "Restarting all necessary services..."
# All necessary services should have already been restarted by now. 