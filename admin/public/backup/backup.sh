#!/bin/bash
# Database credentials
 user="root"
 password="tier5"
 host="127.0.0.1"
 db_name="reviewvelocity"
# Other options
 backup_path="/var/www/html/dev/admin/public/backup/mysql"
 date=$(date +"%d-%b-%Y-%s")
# Set default file permissions
 #umask 177
# Dump database into SQL file
 mysqldump --user=$user --password=$password --host=$host $db_name > $backup_path/$db_name-$date.sql