#!/bin/sh
ln -s /opt/nexus-web-storage /opt/nexus-web/app/sites/default
cd /opt/nexus-web/app
drush cc all
