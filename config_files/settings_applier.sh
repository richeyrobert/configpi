#!/bin/bash
#
# This script will take the settings file that I built in
# and copy it to the actual settings file 
# then this script will restart the interface to apply the settings. 
#
# Make a copy the current interface file...
sudo cp /etc/network/interfaces /etc/network/interfaces.old
# Make a copy of the hosts file
sudo cp /etc/hosts /etc/hosts.old
# Make a copy of the hostname file
sudo cp /etc/hostname /etc/hostname.old
# Move the new interfaces file into place...
sudo cp /var/www/admin/ip-config.txt /etc/network/interfaces
# Move the new hosts file into the correct place
sudo cp /var/www/admin/host-config.txt /etc/hosts
# Move the hostname file into place
sudo cp /var/www/admin/hostname-config.txt /etc/hostname
# Set the proper permissions and ownership of the network interfaces file
sudo chown root:root /etc/network/interfaces
sudo chmod 744 /etc/network/interfaces
# Set the proper permissions and ownership for the hosts file
sudo chown root:root /etc/hosts
sudo chmod 744 /etc/hosts
# Set the proper permossions and ownership for the hostname file
sudo chown root:root /etc/hostname
sudo chmod 744 /etc/hostname
#
# I may need to perform a reboot instead of ifup and ifdown for the settings to actually take effect... Let's experiment and see
# sudo reboot
# Take down the interface...
sudo ifdown eth0
# Bring the new interface back up...
sudo ifup eth0