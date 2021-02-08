# CERN DEV Status

Drupal module that sets a development state to a CERN website. More specifically:

- The website becomes not accessible from outside CERN network
- The website is not tracked by web crawlers (Google index etc)
- Sets up custom IP restrictions
- Adds a banner on the footer of the website informing visitors that the website
is under development

## Getting Started

The aforementioned settings can be set from the settings page of the module 
located in `/admin/config/development/cern-dev-status`

## Versioning

For the versions available, see the 
[tags of this repository](https://gitlab.cern.ch/web-team/drupal/public/d8/modules/cern-dev-status/tags). 

## License

Like [all Drupal themes and modules](https://www.drupal.org/about/licensing), the
Web Team Packages are licensed under the GPL v3.0 license. See the [LICENSE.md](LICENSE.md)
file for more details.