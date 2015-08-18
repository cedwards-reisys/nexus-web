#!/bin/sh
ln -s /opt/nexus-web-storage/files /opt/nexus-web/app/sites/default/files
ln -s /opt/nexus-web-storage/settings.php /opt/nexus-web/app/sites/default/settings.php
cd /opt/nexus-web/app
drush cc all
