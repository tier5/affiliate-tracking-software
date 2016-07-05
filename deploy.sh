#!/bin/bash

# Clear the terminal
clear
cd admin

# Pull the latest
git pull origin develop

# Run migrations
vendor/bin/phinx migrate -e development

# Flush cache
find app/cache -name "*.*" -type f -delete

cd ..



