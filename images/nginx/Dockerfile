ARG NGINX_VERSION

FROM nginx:${NGINX_VERSION}


LABEL io.k8s.description="Drupal managed infra nginx" \
      io.k8s.display-name="Drupal 8 Managed Infra nginx" \
      io.openshift.tags="managed,drupal,nginx" \
      maintainer="Drupal Admins <drupal-admins@cern.ch>"

ENV DRUPAL_SHARED_VOLUME /drupal-data

# Nginx default configuration
ADD config/default.conf /etc/nginx/conf.d/default.conf
ADD config/webdav.conf /etc/nginx/conf.d/webdav.conf
ADD config/nginx.conf /etc/nginx/nginx.conf

### n.b.: https://www.redpill-linpro.com/sysadvent/2017/12/10/jekyll-openshift.html
RUN \
  mkdir -p /etc/nginx/ /var/cache/nginx /var/lib/nginx /var/log/nginx /var/tmp/nginx/ && \
  chgrp -R 0 /etc/nginx/ /var/cache/nginx /var/lib/nginx /var/log/nginx /var/tmp/nginx/ && \
  chmod -R g=u /etc/nginx/ /var/cache/nginx /var/lib/nginx /var/log/nginx /var/tmp/nginx/ && \
  sed -i -e '/^user/d' /etc/nginx/nginx.conf

ADD run-nginx.sh /
RUN chmod +x /run-nginx.sh

ENTRYPOINT ["/run-nginx.sh"]
