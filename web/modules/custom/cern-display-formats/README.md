# CERN Display Formats

Provides a set of display formats designed for the needs of the CERN users.

Until v1.3.0 the implemented display formats are:

* **Accordion**: Renders the content in an accordion type style.
* **Horizontal Boxes**: Renders the content in a style of cards with the option to use carousel to navigate.
* **Accordion**: Renders the view rows in the style of an accordion
* **Teaser List**: Renders the view rows the one under the other with appropriate spacing
* **Collision**: Renders the view rows creating a "collision" effect
* **Countdown**: Renders the view row with a countdown effect
* **Vertical Boxes**: Renders the view rows in a vertical position
* **Featured Banner**: Renders the view using the Featured Banner template

## Getting Started

This module is placed in the CERN infra so if you own a CERN Drupal website, you have this module installed by default. All you have to do is to navigate to /admin/modules and enable the module.

If the module for any reason is not placed under the CERN infra, then you can manually add it by downloading and placing the module under /modules folder of your website.

### Dependencies

The module has the following dependencies:

* **Views**: Core module. You don't have to worry about it.
* **CERN Components**: This dependency exists due to the fact that most of the displays formats use display modes that 
are provided by the cern_components module. **Important Note**: Since version 1.2.0, the CERN Display Formats module 
requires the version 2.4.0 or higher of CERN Components.

## Versioning

For the versions available, see the [tags on this repository](https://gitlab.com/web-team/drupal/internal/d8/modules/display-formats/tags). 


## License

Like [all Drupal themes and modules](https://www.drupal.org/about/licensing), the
Web Team Packages are licensed under the GPL v3.0 license. See the [LICENSE.md](LICENSE.md)
file for more details.