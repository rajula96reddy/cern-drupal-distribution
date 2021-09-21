# This Dockerfile builds the *sitebuilder-base* image that deploys the CERN Drupal distribution
# and serves as a basis for all Drupal websites.
# This image will also be used to server PHP container in the deployment

ARG PHP_VERSION

FROM php:${PHP_VERSION}

# COMPOSER_VERSION and DRUSH_VERSION are passed by Gitlab as an argument through the version files in drupalVersions directory
ARG COMPOSER_VERSION
ARG DRUSH_VERSION


LABEL io.openshift.s2i.scripts-url="image:///usr/libexec/s2i" \
    io.s2i.scripts-url="image:///usr/libexec/s2i" \
    io.k8s.description="Drupal Site Builder s2i build & php-fpm infra" \
    io.k8s.display-name="Drupal Site Builder + php-fpm" \
    io.openshift.tags="builder,drupal,php,php-fpm" \
    maintainer="Drupal Admins <drupal-admins@cern.ch>"

# from https://www.drupal.org/docs/8/system-requirements/drupal-8-php-requirements
# from https://github.com/docker-library/docs/blob/master/php/README.md#supported-tags-and-respective-dockerfile-links
# install some utils
RUN apk --update add \
    # Some composer packages need git    
    git \
    patch \
    curl \
    gettext \
    zip \
    unzip \
    mysql-client \
    jq \
    tzdata \
    rsync

# Configured timezone.
ENV TZ=Europe/Zurich
RUN touch /usr/share/zoneinfo/$TZ \
	&& cp /usr/share/zoneinfo/$TZ /etc/localtime \
	&& echo $TZ > /etc/timezone && \
	apk del tzdata \
	&& rm -rf /var/cache/apk/*

# PHP FPM
RUN set -eux; \
	\
	apk add --no-cache --virtual .build-deps autoconf g++ make \
		coreutils \
		freetype-dev \
		libjpeg-turbo-dev \
		libpng-dev \
		libzip-dev \
		mysql-client \
	; \
    \
    rm -rf /tmp/pear \
    ; \
	\
	docker-php-ext-configure gd \
		--with-freetype-dir=/usr/include \
		--with-jpeg-dir=/usr/include \
		--with-png-dir=/usr/include \
	; \
	\
	docker-php-ext-install -j "$(nproc)" \
		gd \
		opcache \
		pdo_mysql \
		zip \
	; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --virtual .drupal-phpexts-rundeps $runDeps; \
	apk del .build-deps


# RUN mkdir -p /tmp/drush
# WORKDIR /tmp/drush
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer --version=${COMPOSER_VERSION}
# RUN composer clearcache; composer require drush/drush ${DRUSH_VERSION}; composer install;
# ENV PATH=$PATH:/tmp/drush/vendor/bin
COPY ./s2i/bin/ /usr/libexec/s2i/
COPY ./php-fpm/fix-permissions /fix-permissions
RUN chmod -R +x /usr/libexec/s2i/; \
    chmod +x /fix-permissions

# DRUPAL
# Path configuration
# Set up drupal site folder and drupal operations folder
RUN mkdir -p /app; \
    mkdir -p /operations

ENV DRUPAL_APP_DIR /app
ENV DRUPAL_OPERATIONS_DIR /operations

# The following folders are copied from the CI environment during image build 
COPY ../../cern-drupal-distribution ${DRUPAL_APP_DIR}
# Add scripts for Drupal operations
COPY drupal-operations-scripts ${DRUPAL_OPERATIONS_DIR}
RUN chmod +x ${DRUPAL_OPERATIONS_DIR}/*

WORKDIR ${DRUPAL_APP_DIR}

# Create necessary folders from composer
RUN rm -rf .git; \
    mkdir ${DRUPAL_APP_DIR}/.composer; \
    /fix-permissions ${DRUPAL_APP_DIR}/.composer
ENV COMPOSER_HOME=${DRUPAL_APP_DIR} COMPOSER_CACHE_DIR=${DRUPAL_APP_DIR}/.composer

# Do not run Composer as root/super user! See https://getcomposer.org/root for details
# Set up drupal minimum stack
ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --optimize-autoloader -v

ENV PATH=$PATH:${DRUPAL_APP_DIR}/vendor/bin

RUN mkdir -p ${DRUPAL_APP_DIR}/web/themes/custom

# Clean-up composer installation
# Rename composer.json to composer.admins.json so that when user injects its composer,
# there is no conflict between then.
RUN cp ${DRUPAL_APP_DIR}/composer.json ${DRUPAL_APP_DIR}/composer.admins.json

# Put symlinks for profiles/themes/modules to a path where old Drupal sites expect to find them
RUN ln -s contrib/cern-install-profiles/cern ${DRUPAL_APP_DIR}/web/profiles/cern && \
    ln -s contrib/cern-install-profiles/easystart ${DRUPAL_APP_DIR}/web/profiles/easystart && \
    ln -s cern-theme ${DRUPAL_APP_DIR}/web/themes/custom/cernclean &&\
    ln -s cern-base-theme ${DRUPAL_APP_DIR}/web/themes/custom/cernbase

# Add extra configurations
# At this point, composer has created the required settings.php through:
# post-update-cmd: DrupalProject\composer\ScriptHandler::createRequiredFiles
# Overwrite settings.php with ours.
# - settings.php
ADD ./settings.php ${DRUPAL_APP_DIR}/web/sites/default/settings.php
# Remove ${DRUPAL_APP_DIR}/web/sites/default/{files, private, modules, themes}, preparing it to be symbolic link;
RUN rm -rf ${DRUPAL_APP_DIR}/web/sites/default/files; \
    rm -rf ${DRUPAL_APP_DIR}/web/sites/default/private; \
    rm -rf ${DRUPAL_APP_DIR}/web/sites/default/modules; \
    rm -rf ${DRUPAL_APP_DIR}/web/sites/default/themes

# Explicity create the site configuration dir as configured in settings-d8.php#L17 file
RUN mkdir -p config/sync; \
    /fix-permissions ${DRUPAL_APP_DIR}

# Add extra configurations
# At this point, composer has created the required settings.php through post-update-cmd: DrupalProject\composer\ScriptHandler::createRequiredFiles
# Overwrite settings.php with ours.
# - php-fpm
ADD ./php-fpm/config/php-fpm/ /usr/local/etc/php-fpm.d/
# - opcache
ADD ./php-fpm/config/opcache/ /usr/local/etc/php/conf.d/

ENV DRUPAL_SHARED_VOLUME /drupal-data

RUN ln -s ${DRUPAL_SHARED_VOLUME}/files ${DRUPAL_APP_DIR}/web/sites/default/files && \
    ln -s ${DRUPAL_SHARED_VOLUME}/private ${DRUPAL_APP_DIR}/web/sites/default/private && \
    ln -s ${DRUPAL_SHARED_VOLUME}/modules ${DRUPAL_APP_DIR}/web/sites/default/modules && \
    ln -s ${DRUPAL_SHARED_VOLUME}/themes ${DRUPAL_APP_DIR}/web/sites/default/themes

# The directory sites/default is not protected from modifications and poses a security risk.
# Change the directory's permissions to be non-writable is needed.
RUN chmod -R 555 ${DRUPAL_APP_DIR}/web/sites/default
RUN chmod 444 ${DRUPAL_APP_DIR}/web/sites/default/settings.php

CMD ["/usr/libexec/s2i/usage"]
