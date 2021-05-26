#!/bin/sh
set -xm

echo "---> Running PHP-FPM..."
rm "$PHP_PID_FILE"
php-fpm &

# Setup symlinks for file from PHP process fs
export PHP_PID_FILE=/var/run/php-fpm.pid

# retry 3 times and if not exit with a error message
for i in {1..3}
do
[ -s "$PHP_PID_FILE" ] || sleep "1s"
done

if [ -s "$PHP_PID_FILE" ]
then
    php_pid=$(cat $PHP_PID_FILE)
    ln -s /proc/"$php_pid"/root/app /var/run/app
else
    echo "Failed to read PHP pid file and create a symlink"
    exit 0;
fi

tail -f /proc/$(cat $PHP_PID_FILE)/fd/2
