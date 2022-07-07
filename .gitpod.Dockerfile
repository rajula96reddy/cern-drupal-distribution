FROM gitlab-registry.cern.ch/drupal/paas/cern-drupal-distribution/site-builder:php-8.1-b43f6a7a

RUN addgroup --gid 33333 gitpod \
    && adduser -h /home/gitpod -u 33333 \
        -G gitpod -D gitpod