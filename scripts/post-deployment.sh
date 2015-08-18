#!/bin/sh

if [ ! -L /opt/nexus-web/app/sites/default/files ]; then
  ln -s /opt/nexus-web-storage/files /opt/nexus-web/app/sites/default/files
fi

if [ ! -L /opt/nexus-web/app/sites/default/settings.php ]; then
  ln -s /opt/nexus-web-storage/settings.php /opt/nexus-web/app/sites/default/settings.php
fi

cd /opt/nexus-web/app
drush cc all
