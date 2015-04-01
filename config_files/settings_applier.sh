#!/bin/bash
#
# This script will take the settings file that I built in
# and copy it to the actual settings file 
# then this script will restart the interface to apply the settings. 
#
# Make a copy the current interface file...
cp /etc/network/interfaces /etc/network/interfaces.old
# Move the new interfaces file into place...
cp ip-config.txt /etc/network/interfaces
# Set the proper permissions and ownership of the new file...
chown root:root /etc/network/interfaces
chmod 744 /etc/network/interfaces
# Take down the interface...
ifdown eth0
# Bring the new interface back up...
ifup eth0