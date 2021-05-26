# Drupal Runtime

This repository defines the runtime environment of the Drupal infrastructure,
orchestrated by the [Drupal Site Operator](https://gitlab.cern.ch/drupal/paas/drupalsite-operator/).
It includes:

- [drupalVersions](drupalVersions): list of different drupal version files, which also defines configuration for different versions
- [runtime configuration](configuration): configmaps for the server software stack (nginx/php)
- Dockerfiles and source-to-image (s2i) recipes to build the images that eventually run on the infrastructure

The images in this repo are the sources of BuildConfigs that generate the actual runtime images for every Drupal site
based also on extra configuration injected by the users.

## Supporting a new `drupalVersion`

To let DrupalSites use a new value for `drupalVersion`, create a new file in [`drupalVersions/${drupalVersions}`](drupalVersions)
folder.


Example format:
```
  variables:
      drupalDistroRefspec: '9.1.x'
      phpVersion: '7.3.23-fpm-alpine3.12'
      nginxVersion: '1.18.0'
      composerVersion: '2.0.0'
      drushVersion: '10.3.6'
```

Notes:
- the name of the file itself will be used as the value for `drupalVersion`
- the indentation is important, since this will be injected
- all the files under `drupalVersions` are what generates the "registry" of all the website configurations supported by the Drupal infrastructure.


## CI & Registry

Drupal runtime CI builds 2 images for every commit in this registry, for a given "Drupal version" from the files in [drupalVersions](drupalVersions).

- [nginx](./nginx/Dockerfile)
    - Based on upstream Nginx and configures nginx for openshift
- [site-builder-base](./Dockerfile)
    - Based on the `php` upstream image, installs required php dependencies, copies the s2i scripts and also builds the basic drupal project cloned from [cern-drupal-distribution](https://gitlab.cern.ch/drupal/paas/cern-drupal-distribution)

The [generate-pipeline.sh](generate-pipeline.sh) file creates stages on the fly from each of the version files and upon creation of the stages, the two image build stages namely `Build-nginx` and `Build-sitebuilder-base` run in parallel

![Pipeline](./pipeline.png)

### <a id="tags"></a> Image Tags

commit location | tag
--- | ---
master | `<drupalVersion-file-name>`
other branch/ MR | `<drupalVersion-file-name>-dev-<commit-short-sha>`

Every time a commit is pushed, CI generates 1 tag for each of the 2 images
**for each file in `drupalVersions` directory**.
CI not running on master also appends the commit sha to the generated tags.
This creates a lot of tags, but insulates testing from production.

The image tags created and the registry to which it is pushed, will be echoed at the end of a successful pipeline 
#### Creating new tags

Simply push a commit to this repo.
To create "production" tags (ie. `<drupalVersion>` instead of `<drupalVersion>-dev-<commit-sha>`, merge to master.

### Build arguments and CI variables

The following table lists the build arguments and variables used in the Dockerfiles and the CI.

| Variable | Description |
| ----------- | ----------- |
| COMPOSER_VERSION | Controls the composer version to be installed |
| NGINX_VERSION | Controls the nginx image version to be pulled |
| PHP_VERSION | Controls the php image version to be pulled |
| DRUSH_VERSION | Controls the drush version to be used |
| DRUPAL_APP_DIR | The directory that hosts the drupal code |
| DRUPAL_SHARED_VOLUME | Mount path of persistent volume |
| SITE_BUILDER_DIR | This directory points to the location, where the drupal code will be available to be copied from in the Openshift buildConfig |
| CI_COMMIT_REF_NAME | Gitlab tag/ branch name |

## Configuration
### PHP

Global default configuration in `php-fpm/config` is baked into the sitebuilder-base image

QOS-specific config in each of the `configuration/qos*/php-fpm.conf` directories is mounted as configmap

### Nginx

Global default configuration in `nginx/config` is baked into the nginx image

QOS-specific config in each of the `configuration/qos*/nginx.conf` directories is mounted as configmap

## Architecture of a Drupal project

This diagram shows an exploded view of the parts of a Drupal site and the images they need.
These images are built by this repo.

![Architecture Diagram](https://gitlab.cern.ch/paas-tools/okd4-install/-/raw/master/docs/cluster-flavours/drupal/drupal-design.svg)

Ref: https://gitlab.cern.ch/paas-tools/okd4-install/-/tree/master/docs/cluster-flavours/drupal

