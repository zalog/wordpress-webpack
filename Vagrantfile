require 'json'

settings = JSON.parse(File.read(File.expand_path "./package.json"))

DB_NAME = "wordpress"
DB_PASS = "root"
WP_URL = "http://localhost"
WP_USER = "admin"
WP_PASS = "admin"
WP_EMAIL = "admin@localhost.dev"
WP_THEME = settings['name']



Vagrant.configure("2") do |config|
  config.vm.box = "bento/ubuntu-16.04"

  config.vm.network "forwarded_port", guest: 80, host: 8080

  config.vm.network "forwarded_port", guest: 3000, host: 3000, auto_correct: true
  config.vm.network "forwarded_port", guest: 3001, host: 3001, auto_correct: true

  config.vm.synced_folder ".", "/vagrant", disabled: true
  config.vm.synced_folder "./src", "/vagrant/src"
  config.vm.synced_folder "./.build", "/vagrant/.build"
  config.vm.synced_folder "./.data", "/vagrant/.data"

  config.vm.provision "shell",
    env: {
      "DB_PASS" => DB_PASS
    },
    inline: <<-SHELL
      # user group & profile
      apt-get update # && apt-get upgrade -y
      apt-get install curl
      usermod -a -G www-data vagrant
      chown -R vagrant:www-data /vagrant

      # apache
      apt-get install -y apache2
      a2enmod rewrite
      sed -i "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf
      sed -i 's/^export APACHE_RUN_USER*=.*$/export APACHE_RUN_USER=vagrant/g' /etc/apache2/envvars
      sed -i 's/^export APACHE_RUN_GROUP*=.*$/export APACHE_RUN_GROUP=vagrant/g' /etc/apache2/envvars
      if ! [ -L /var/www ]; then
        rm -rf /var/www/html/*
        chown -R vagrant:www-data /var/www/html
      fi
      service apache2 restart

      # php
      apt-get install -y php7.0 libapache2-mod-php7.0
      apt-get install -y php-curl php-gd php-mbstring php-mcrypt php-xml php-xmlrpc
      sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php/7.0/apache2/php.ini
      sed -i "s/display_errors = .*/display_errors = On/" /etc/php/7.0/apache2/php.ini
      sed -i "s/upload_max_filesize = .*/upload_max_filesize = 512M/" /etc/php/7.0/apache2/php.ini
      sed -i "s/post_max_size = .*/post_max_size = 512M/" /etc/php/7.0/apache2/php.ini
      sed -i "s/[; ]*max_input_vars = .*/max_input_vars = 5000/" /etc/php/7.0/apache2/php.ini
      service apache2 restart

      # mysql
      debconf-set-selections <<< "mysql-server mysql-server/root_password password $DB_PASS"
      debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $DB_PASS"
      apt-get install -y mysql-server

      # phpmyadmin
      debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true"
      debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password $DB_PASS"
      debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password $DB_PASS"
      debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password $DB_PASS"
      debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect none"
      apt-get install -y phpmyadmin

      # wp-cli
      cd /tmp && curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
      chmod +x wp-cli.phar
      mv wp-cli.phar /usr/local/bin/wp

      # nodejs
      curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -
      apt-get install nodejs

      # gulp
      npm install -g gulp
    SHELL

  config.vm.provision "file", source: "./gulpfile.js", destination: "/vagrant/gulpfile.js"
  config.vm.provision "file", source: "./package.json", destination: "/vagrant/package.json"

  config.vm.provision "install",
    type: "shell",
    privileged: false,
    env: {
      "DB_NAME" => DB_NAME,
      "DB_PASS" => DB_PASS,
      "WP_URL" => WP_URL,
      "WP_USER" => WP_USER,
      "WP_PASS" => WP_PASS,
      "WP_EMAIL" => WP_EMAIL,
      "WP_THEME" => WP_THEME
    },
    inline: <<-SHELL
      # profile
      echo 'cd /vagrant' >> ~/.bashrc

      # package install
      cd /vagrant
      npm install
      gulp dist

      # wordpress
      cd /var/www/html
      wp core download
      wp config create --dbname=$DB_NAME --dbuser=root --dbpass=$DB_PASS
      wp db create
      wp core install --url=$WP_URL --title=$WP_THEME --admin_user=$WP_USER --admin_password=$WP_PASS --admin_email=$WP_EMAIL
      rm -rf /var/www/html/wp-content/plugins && ln -sf /vagrant/.data/plugins /var/www/html/wp-content
      rm -rf /var/www/html/wp-content/uploads && ln -sf /vagrant/.data/uploads /var/www/html/wp-content
      # wordpress imports
      # wp db reset --yes && wp db import /vagrant/.data/db.sql
      # wp option update siteurl $WP_URL
      # wp option update home $WP_URL
      # wp rewrite flush
      # wp user create $WP_USER $WP_EMAIL --role=administrator --user_pass=$WP_PASS
      # wp plugin install advanced-custom-fields cmb2 tiny-compress-images wordpress-seo yet-another-related-posts-plugin youtube-live-stream-auto-embed --activate
      wp user update 1 --show_admin_bar_front=false --rich_editing=false
      ln -sf /vagrant/dist /var/www/html/wp-content/themes/$WP_THEME
      wp theme activate $WP_THEME
    SHELL
end
