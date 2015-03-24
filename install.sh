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
#
# 1. Make sure that the script is being run by a root user
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root" 1>&2
   exit 1
fi
#
# 2. Make sure that the proper packages are installed
echo "Making sure that the proper packages are installed..."
apt-get install -y #< Insert the proper packages here >
#
# 3. Move all of the scripts and the web pages into the correct locations
echo "Making sure all the files are in the proper locations..."
#
# 4. Make sure all of the scripts are executable
echo "Making sure all proper scripts are executable..."
#
# 5. Make sure all of the files have the proper permissions
echo "Making sure all of the files have the proper permissions..."
#
# 6. Make the necessary changes to any config files
echo "Making the necessary changes to any config files..."
#
# 7. Make the default usernames and passwords for the web interface
# Prompt user for username and password and password confirmation...
echo "Creating the default usernames and passwords..."
#
# 8. Restart all of the necessary services
echo "Restarting all necessary services..."
#