#!/bin/bash
# Script to move Laravel from subdirectory to root properly

echo "Moving Laravel to document root..."

# Backup current setup
mkdir -p /home/mobiplay/backup
cp -r /home/mobiplay/public_html /home/mobiplay/backup/

# If Laravel is in a subdirectory, move it to root
if [ -d "/home/mobiplay/public_html/Mobiplay-Web" ]; then
    echo "Moving from Mobiplay-Web subdirectory..."
    mv /home/mobiplay/public_html/Mobiplay-Web/* /home/mobiplay/public_html/
    mv /home/mobiplay/public_html/Mobiplay-Web/.* /home/mobiplay/public_html/ 2>/dev/null || true
    rmdir /home/mobiplay/public_html/Mobiplay-Web
fi

# Set correct permissions
chmod -R 755 /home/mobiplay/public_html
chmod -R 775 /home/mobiplay/public_html/storage
chmod -R 775 /home/mobiplay/public_html/bootstrap/cache

echo "Laravel moved to root. Update your domain's document root to point to /home/mobiplay/public_html/public/"