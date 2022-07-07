FROM gitlab-registry.cern.ch/drupal/paas/cern-drupal-distribution/site-builder:php-8.1-4b4a2ec5

RUN addgroup --gid 33333 gitpod \
    && adduser -h /home/gitpod -u 33333 \
        -G gitpod -D gitpod

# /var/run directory for process pids
RUN mkdir -p /var/run

# Copy config files like we do in configmaps
COPY images/nginx/config/nginx-global-default.conf /etc/nginx/global.conf
COPY images/php-fpm/config/php-fpm/www.conf /usr/local/etc/php-fpm.d/zz-docker.conf
COPY images/settings-default.php /app/web/sites/default/settings.php
# COPY  /usr/local/etc/php/conf.d/config.ini

# Dazzle does not rebuild a layer until one of its lines are changed. Increase this counter to rebuild this layer.
ENV TRIGGER_REBUILD=1

# Install MySQL
RUN apk add mysql mysql-client \
 && mkdir -p /var/run/mysqld /var/log/mysql \
 && chown -R gitpod:gitpod /etc/mysql /var/run/mysqld /var/log/mysql /var/lib/mysql

# Install our own MySQL config
COPY .gitpod/mysql.cnf /etc/mysql/mysql.conf.d/mysqld.cnf

# Install default-login for MySQL clients
COPY .gitpod/client.cnf /etc/mysql/mysql.conf.d/client.cnf


# COPY .gitpod/mysql-bashrc-launch.sh /etc/mysql/mysql-bashrc-launch.sh

# USER gitpod

# RUN echo "/etc/mysql/mysql-bashrc-launch.sh" >> /home/gitpod/.bashrc.d/100-mysql-launch