# nexus-web

auto deploy working....

## Site Setup

1) clone repository

2) create a database in mysql. Ex:
```
mysql -uroot -e "CREATE SCHEMA nexus_web;"
```

3) create a local dev domain name by inserting a line into /etc/hosts. Ex: 
``` 
127.0.0.1    nexus-web.dev
```
4) create a vhost for apache.  
In the directory: /etc/apache2/httpd.conf
Add the following to the end of the file.  Give DocumentRoot the path to the cloned repository app directory.  
```
<VirtualHost *:80>
    DocumentRoot "/Users/yourusername/Sites/nexus-web/app" 
    ServerName nexus-web.local

  <Directory /Users/yourusername/Sites/nexus-web/app>
    Options +FollowSymLinks
    AllowOverride All
    order allow,deny
    allow from all
  </Directory>
</VirtualHost>
```
When done, restart apache.
```
sudo apachectl restart
```

5) Visit http://nexus-web.dev and there should be a drupal install screen.

6) Once installed, enable the jquery update module and make the bootstrap theme active for both user and admin. 
