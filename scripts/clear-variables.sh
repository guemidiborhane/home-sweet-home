# Clear The Old Environment Variables

sed -i '/# Set Sweethome Environment Variable/,+1d' /home/vagrant/.profile
sed -i '/env\[.*/,+1d' /etc/php5/fpm/php-fpm.conf
