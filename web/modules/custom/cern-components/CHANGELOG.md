# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.7.10] - 14/01/2021
- Update module to be D9-ready

## [2.7.9] - 12/01/2021

- Add id attribute in section (row) component generated from title
- Fix mega menu items not being clickable when boxes are in background (z-index issue)

## [2.7.8] - 17/12/2020

- Fix Article Boxes not getting selected size of CDS images

## [2.7.7] - 16/12/2020

- Add id attribute in sections that are linked from scrolling menu

## [2.7.6] - 04/12/2020

- Add composer.json file

## [2.7.5] - 28/10/2020

- Fix image gallery component not rendering thumbnails in Chrome/Safari

## [2.7.4] - 21/09/2020

- Fix expansion of accordion items when an accordion item is rendered multiple items in the same page
- Fix Agenda Box to be working with Mathjax library
- Increase height of half height sections

## [2.7.3] - 06/08/2020

- Fix white spaces appearing above Hero Header is some cases
- Fix Fix half height background images of sections not appearing in mobile resolutions

## [2.7.2] - 18/06/2020

- Fixed Related Card/Preview Card components not receiving correct colors when used in Landing Pages
- Fixed issue of half height images w/o has Header looking squashed
- Removed enforcing of width in image field of News pattern (overriding drupal image style)
- Fixes background image in centered section overflows

## [2.7.1] - 25/05/2020

- Fixed box link for both link and title pattern fields
- Modified the way links wrap the boxes to be done using HTML instead of JS
- Increased z-index of scrolling menu to appear above sections in all cases
- Fixed Preview List Date/Topic/Format pattern fields to always appear inline
- Added margins in date field of News Display pattern
- Modified Hero Frame subtitle to hold 100% of height
- Fixed items placed in right sidebar of Section not receiving the correct classes
- Fixed Margin Component opacity not working when set to 0
- Modified Event pattern to render breadcrumbs only if /events url exists
- Fixed Call to Action Buttons not having space between them in Safari

## [2.7.0] - 09/03/2020

- Implemented Show/Hide section functionality in section component
- Fixed Scrolling Menu appearing on top of expanded menu
- Modified cards/boxes to be fully clickable, not only the title
- Reduced test-shadow of cards/boxes
- Fixed half-height in mobile devices when subtitle is not present
- Fixed margin-top of Hero Frame title when has-header is disabled

## [2.6.4] - 28/11/2019

- Fixed configuration of section columns when having content in all 3 columns

## [2.6.3] - 30/09/2019

- Fixed issue of parallax effect having weird jumping effect in small resolutions

## [2.6.2] - 30/09/2019

- Fixed issue of node fields not being updated when translating a resource from CDS

## [2.6.1] - 12/08/2019

- Fixed issue of Header Block [Hero Frame] overlapping menu

## [2.6.0] - 18/07/2019

- Changed implementation of Agenda Box to include word "Event" as default
- Fixed issue of Event Full Content pattern not displaying link in default event_type
- Fixed News pattern (News Full content pattern) to display breadcrumbs only if both news_format and topic are present
- Applied same render stylings as Preview Card in the rest of the boxes (word crop and text-shadow)
- Added consistent colors for patterns to be used in display formats

## [2.5.2] - 27/06/2019

- Fixed issue of countdown not working with timezones [hardcoded Geneva timezone]

## [2.5.1] - 09/05/2019

- Fixed issue of mega menu not working on specific versions of IE


## [2.5.0] - 25/04/2019

- Fixed issue of plus/minus signs in FAQ-list pattern
- Fixed issue of active trail in 2nd level menu


## [2.4.0] - 26/02/2019

- Fixed issue of Preview Card styling not loading on Preview List
- Fixed error when placing date format under date in Preview Card
- Added color for preview-list titles
- Added text-shadow on Preview Cards' elements
- Removed top margin from Preview List component


## [2.3.2] - 31/01/2019

- Fixed issue of video resources are displayed very slim

## [2.3.1] - 30/01/2019

-  Decreased the number of permitted chars for Preview cards from 65 to 45

## [2.3.0] - 21/01/2019

- Applied 'CDS-loading-error' to all thumbnail items instead of only affected images
- Fixed issue og Preview cards overflowing under mega menu
- Added text shadow for Hero Frames titles and subtitles


## [2.2.2] - 16/01/2019

- Fixed overflowing text in Preview Cards when the card had icon

## [2.2.1] - 10/12/2018

- Added styling for "+More" link in mega menu


## [2.2.0] - 06/12/2018

- Fixed FAQ List pattern not holding 100% of the width
- Fixed scroll button being cut on mobile chrome
- Fixed image Gallery stretching
- Added responsive version in Preview List pattern
- Added border around Preview List images
- Added new twig function that returns current language
- Changed design of FAQ List pattern
- Removed avatar from "opinion" pieces

