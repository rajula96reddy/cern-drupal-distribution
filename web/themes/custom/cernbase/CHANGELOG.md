# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.6.3] - 22/01/2021
- Apply fix for unordered lists with long text not being aligned

## [2.6.2] - 18/01/2021

- Update theme to be compatible with Drupal 9

## [2.6.1] - 07/12/2020

- Add composer.json file

## [2.6.0] - 06/03/2020

- Removed file size next to file link so that it will not appear twice when in table of files.
- Modified implementation of search button on header to hijack screen for search functionality.
- Modified menus on sidebars to be consistent and using same designs
- Removed copyright color styling from CERN Base
- Added missing styles for inline labels (render inline and bold font weight)
- Updated bootstrap theme to v3.4.1
- Modified order Bootstrap libraries are defined (Fixes JS Aggregation issue)

## [2.5.9] - 17/01/2020

- Fixed long links in pages overflow outside container
- Fixed full pager in sidebars appearing broken (not inline / wrong spacing)

## [2.5.8] - 12/11/2019

- Removed hardcodings of FAQ page about filter form sizing
- Fixed spacings in exposed filters

## [2.5.7] - 22/10/2019

- Changed width of exposed filters under view pages to be set based on number of filters
- Changed implementation of text-based filter when set as first field.

## [2.5.6] - 08/10/2019

- Fixed boxes under .vertical-boxes appearing squashed

## [2.5.5] - 03/10/2019

- Modified letter-spacing and text-shadow of menu items

## [2.5.4] - 03/10/2019

- Fixed helptext in webforms not appearing in some elements
- Reduced title size

## [2.5.3] - 30/09/2019

- Fixed dropdown menu spacing issues

## [2.5.2] - 13/09/2019

- Modified main menu items to have shadow only when has-header is on
- Implemented .block-sticky-bottom class for sticky blocks at the bottom of the page

## [2.5.1] - 27/08/2019

- Fixed custom block title not displaying in sidebars

## [2.5.0] - 18/07/2019

- Fixed issue of toolbar not working in non-CERN themes
- Added event-grid as an event view style
- Added styles for blocks under sidebars
- Added style support for Webform module

## [2.4.1] - 10/05/2019

- Fixed error in view pages

## [2.4.0] - 24/04/2019

- Added support for 3rd+ level menu
- Fixed issue with dual titles in page view
- Removed JS code related to Accordion component

## [2.3.1] - 20/03/2019

- Fixed hovering menu when scrolling

## [2.3.0] - 13/02/2019

- Removed unnecessary template files
- Changed menu expanding from clicking to hovering


## [2.2.2] - 05/02/2019

- Fixed preview cards under horizontal boxes not holding 100% of width when set to render using rendered entity.

## [2.2.2] - 05/02/2019

- Fixed issue with preview cards under horizontal boxes not holding 100% of width when set to render using rendered entity.

## [2.2.1] - 05/02/2019

- Added stylings for view header placed under content footer region

## [2.2.0] - 21/01/2019

- Fixed boxes not holding 100% in horizontal boxes.
- Fixed links in views under page display having big font size.
- Decreased margin between menu elements so that they they won't drop in the next line if there is not enough space.
- Added Feature for breaking slogan to the next line if there is not enough space
- Removed CSS ellipses from resources preview-cards

## [2.1.0] - 06/12/2018

- Fixed issue with non-aligned filters
- Fixed issue with overlapping column (Teaser List)
- Fixed issue with Horizontal Boxes height
- Fixed CERN loader rendering upside down
- Fixed filters support regardless number of filters (Teaser List)
- Fixed Image Gallery stretching
- Added custom classes for view block styling of teaser lists
- Added prettier borders to agenda views
- Added border around news images
- Removed weird spinning spans behind CDS images
