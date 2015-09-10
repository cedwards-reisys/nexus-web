#!/usr/bin/env bash

if [ ! -L /opt/nexus-web/app/sites/default/files ]; then
  ln -s /opt/nexus-web-storage/files /opt/nexus-web/app/sites/default/files
fi

if [ ! -L /opt/nexus-web/app/sites/default/settings.php ]; then
  ln -s /opt/nexus-web-storage/settings.php /opt/nexus-web/app/sites/default/settings.php
fi

cd /opt/nexus-web/app
drush -y updatedb
drush cc all

cp -f /opt/nexus-forum-storage/config.php /opt/nexus-web/app/community/config.php
cp -f /opt/nexus-forum-storage/core/config.php /opt/nexus-web/app/community/core/includes/config.php
rm -rf /opt/nexus-web/community/core/install
