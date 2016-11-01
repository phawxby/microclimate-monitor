#!/usr/bin/env bash

# Use single quotes instead of double quotes to make it work with special-character passwords
PASSWORD='12345678'

sudo apt-get update
sudo apt-get -y upgrade

sudo apt-get install -y apache2
sudo apt-get install -y php5

sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $PASSWORD"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $PASSWORD"
sudo apt-get -y install mysql-server
sudo apt-get install php5-mysql

sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2"
sudo apt-get -y install phpmyadmin

# setup hosts file
VHOST=$(cat <<EOF
<VirtualHost *:80>
    DocumentRoot "/var/www/html/public"
    <Directory "/var/www/html/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF
)
echo "${VHOST}" > /etc/apache2/sites-available/000-default.conf

# enable mod_rewrite
sudo a2enmod rewrite

# restart apache
service apache2 restart

# remove default apache index.html
sudo rm "/var/www/html/index.html"

# run SQL statements from MINI folder
sudo mysql -h "localhost" -u "root" "-p${PASSWORD}" < "/var/www/html/_install/01-create-database.sql"
sudo mysql -h "localhost" -u "root" "-p${PASSWORD}" < "/var/www/html/_install/02-create-table-song.sql"
sudo mysql -h "localhost" -u "root" "-p${PASSWORD}" < "/var/www/html/_install/03-insert-demo-data-into-table-song.sql"

# put the password into the application's config. This is quite hardcore, but why not :)
sudo sed -i "s/your_password/${PASSWORD}/" "/var/www/html/application/config/config.php"

# final feedback
echo "Voila!"
