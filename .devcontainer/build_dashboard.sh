#!/bin/bash

# Create necessary directories
mkdir -p /var/www/geocloud2/public/dashboard
mkdir /dashboardtmp

# Clone the repository
cd /dashboardtmp
git clone https://github.com/mapcentia/dashboard.git

# Install dependencies and build the project
cd /dashboardtmp/dashboard
npm install
cp ./app/config.js.sample ./app/config.js
cp ./.env.production ./.env
npm run build

# Copy built files to the target directory
cp -R ./build/* /var/www/geocloud2/public/dashboard/

# Clean up temporary directory
rm -R /dashboardtmp
