# CERN Drupal Distribution

A Composer package that defines the CERN Drupal Distribution: Drupal and a set of core modules,
that are the common denominator of all websites deployed in the [CERN Drupal service](https://drupal.cern.ch).

This composer project relies on an upstream recommended setup for Drupal sites.
More info in [cern-drupal-distribution-composer-project.md](cern-drupal-distribution-composer-project.md).

#### Customizing the composer project of my website

First refer to the DrupalSite docs.

Extra composer packages can be injected to a website by linking the DrupalSite to a git repo with a `composer.json`.
The injection happens with a [source-to-image](images/s2i) mechanism, triggered when the DrupalSite is deployed.

## Sitebuilder/nginx images

This composer project is deployed through a [sitebuilder image](images/Dockerfile-sitebuilder).
Each DrupalSite instantiated at the CERN Drupal service deploys a sitebuilder and an [nginx](images/nginx/Dockerfile) container.

The rest of the [images](images) directory includes configuration to build the images.
The image tags depend on the [release process](#release)

## Development / updating environment

A specific version of composer is ideal to work with this repo. We have seen cases where different composer versions produce incompatible composer.lock
In order to standardize development, we produce an intermediate [composer-builder](images/Dockerfile-composerbuilder) image, from which the sitebuilder derives.
Since it is the base of the sitebuilder, this image should be also used as a development environment.
The image tag that is used by the sitebuilder is listed in [software versions](images/softwareVersions) as `composerBuilderTag`.

To create a local development environment with the same composer version the following can be used, replacing the tag as needed:

```bash
$ docker run -it -v .:/project:z gitlab-registry.cern.ch/drupal/paas/cern-drupal-distribution/composer-builder:v9.3-1-RELEASE-2022.03.10T15-05-23Z
# composer -n require ...
# COMPOSER_MEMORY_LIMIT=-1 composer -n update -v --optimize-autoloader --with-all-dependencies
```

Note that composer needs a lot of memory to run updates, so it's recommended to remove the memory limit
by setting the environment variable `COMPOSER_MEMORY_LIMIT=-1`.

To see the changes to the actual versions after updating, run:
```
git diff composer.lock | egrep -B1 '(\+|-)            "version":'
```

## <h2 id="release"></h2> Versioning and Release

The precise contents of the images currently running a Drupal site can be linked to this repo.
Each branch in this repo starting with the letter `v*` is a stable version branch.
The branch's name corresponds to the `spec.version.name` field in the DrupalSite.

For a standard user, this is all that is needed to instantiate the Drupal site: the latest release (commit)
of the specified branch will be deployed automatically.
The resolved/currently running release (version + releaseSpec) can be found in `DrupalSite.status.releaseID.current`.

The following section gives a detailed description of the GitOps workflow of this repo.

### GitOps (details)

The tip of the repo's history is the `master` branch.
At each point in time we also have some supported version branches (v8.9-1, v8.9-2 in this example):

```
master   v8.9-2 v8.9-1
|
|        |              dev8.9-1-secfix3
                |<----- |
|
                        |
|        |      | ----->|
| ------>|
|               | (backported security fix)
|
|
| ------------->|
|
|     dev-myfeature
|<--- |
|     |
| --->|
|
```

For each commit in a version branch the corresponding image tag is: `<branch>-RELEASE.<date>`

#### Development

Development happens on the master branch using feature branches (`dev-myfeature` in the example above).
Backporting happens in feature branches (`dev8.9-1-secfix3`) that branch out from the version branches.
Each non-version branch produces image tags with each commit of the format `<branch>-<commit-sha>`.

#### Testing

Eventually, CI should run a test suite for every commit against the drupal-stg cluster.
CI should be a normal user that creates a DrupalSite with this version/release and runs tests against it.

 ⚠️  Important Note:
 Currently our Nginx image is merged into sitebuilder, as such a few points to
 take into consideration.
 One, our image implementation is inspired by https://github.com/bkuhl/fpm-nginx (That does not differ from Nginx supported [image](https://github.com/nginxinc/docker-nginx/blob/master/stable/alpine/Dockerfile)).
 Two, Nginx might break CI if stable upstream changes, to fix CI please update the local DockerFile according to upstream.

## Upgrade version constraints of included Composer packages

Occasionally we would like to try upgrading all the bundled packages to the latest version that doesn't break the CERN Drupal Distribution.
This will become easier with more thorough CI testing.

Workflow:
1. Update all version constraints in composer.json to the newest available.
1. Let CI tests confirm that the generated websites aren't breaking. Test thoroughly in coordination with the Web team all the bundled functionality.
1. As we iterate on the changes, it is likely that some of the upgrades may have to be rolled back.

To update all version constraints to the newest:

```bash
jq -r '.require | keys[]' composer.json | xargs composer require
```
