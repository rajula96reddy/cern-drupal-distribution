# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.6.9] - 18/01/2021

- Update theme to be D9-ready

## [2.6.8] - 07/12/2020

- Add composer.json file

## [2.6.7] - 22/09/2020

- Fix agenda-box colors when rendered using the Vertical Boxes format

## [2.6.6] - 21/09/2020

- Fix styling of menu items that are not links in sidebars
- Make menu items that are not links appear as pointer on hover
- Fix sidebar region styles not being applied in sidebars of landing pages
- Fix sidebar dropdowns appearing on top of menu (z-index issue)
- Fix site logo not holding full space
- Fix required field form asterisk not being aligned in some field types
- Fix anchors not leading to correct position due to header offset
- Fix menu items that are not links do not have text shadow
- Add colors for block-sticky-bottom block
- Fix shadows in blocks placed in sidebars of landing pages
- Apply fix for non text field exposed filter rendered with magnifier icon
- Add initial styles for comments

## [2.6.5] - 18/06/2020

- Added translation option for site name/site motto/SEARCH button
- Changed view tables to inherit correct colors of CERN Theme
- Fixed color of cards in views
- Fixed shadows in right/left sidebars blocks/views and added class "without-shadow" for optional removal

## [2.6.4] - 25/05/2020

- Fixed broken view filters after update 2.6.0
- Fixed overflow of underline menu items in Firefox
- Fixed collapsible dropdown menus having gap between 2nd and 3rd level
- Aligned spacings menus/view blocks/custom blocks when placed on sidebar regions
- Added box-shadow for view blocks/custom blocks when placed on sidebar regions
- Fixed Language Switcher not appearing in mobile devices when menu is open
- Fixed site name not displaying the correct site name but overridden value (using getRawData() function)
- Fixed coloring and styling of menu items that are not links
- Fixed view header colors not inheriting color from color palette
- Fixed mini pager rendering First and Last items
- Added delay on unhover of menu items to fix submenus hiding even when hiding by mistake
- Fixed Preview Card subtext not inheriting correct colors due to different wrapper tags
- Fixed nested elements in lists not appearing inline with list marker
- Modified description in Search dropdown overlay to be translatable
- Removed underline from links in custom tables

## [2.6.3] - 10/03/2020

- Adjusted spacings of menu items in big displays
- Fixed color of chevron on hover for menus placed on sidebars
- Fixed underline distance of 3rd+ level menu items
- Fixed gap when collapsing burger menu in specific cases

## [2.6.2] - 09/03/2020

- Fixed underline in multiline menu items when hovering

## [2.6.1] - 06/03/2020

- Fixed consistency of underline in mobile devices
- Housekeeping in `header.scss` file (devide + encapsulate responsive cases)
- Fixed alignment of likert elements
- Modified required field representation in webforms

## [2.6.0] - 02/03/2020

- Modified burger menu to be appearing when width <= 1080
- Fixed underline of top-level menus exceeding the word
- Modified colors of menus in sidebars inheriting the colors of menu
- Modified implementation of search button on header to hijack screen for search functionality.
- Fixed taxonomy label background color and sizing
- Fixed sidebar menus overflowing in small resolutions
- Increased width of dropdown menus in main menu
- Aligned spaces in sidebar blocks (menus and custom blocks)

## [2.5.3] - 31/01/2020

- Fixed issue with main menu rendering collapsed when placed on sidebars


## [2.5.2] - 22/01/2020

- Fixed configuration warning "User warning: The following theme is missing from the file system: cern_components in drupal_get_filename()" by removing dependency to cern_components

## [2.5.1] - 23/10/2019

- Fixed cards in teaser-list not inheriting colors from theme color scheme settings

## [2.5.0] - 04/10/2019

- Modified theme appearance settings
- Aligned copyright color w/ logo on footer
- Removed vertical line before language switcher if menu is not present
- Modified paddings and header sizes for blocks under sidebars
- Changed Burger menu color to be consistent with menu link colors.

## [2.4.7] - 30/09/2019

- Fixed spacing in dropdown elements
- Fixed margin-bottom of menus under sidebars
- Fixed underline on hover in dropdown menus overflowing word

## [2.4.6] - 13/09/2019

- Modified main menu to have text-shadow only when has-header is present

## [2.4.5] - 13/09/2019

- Fixed menu layout in small resolutions. Now becomes two lines only if logo + name are set


## [2.4.4] - 28/08/2019

- Fixed line under footer block titles expanding to 100% in small resolutions

## [2.4.3] - 12/08/2019

- Fixed scroll bar appearing in IE next to site name
- Fixed space between logo and site name in md devices (added offset)

## [2.4.2] - 25/07/2019

- Fixed issue of footer menus not rendering correctly after last update

## [2.4.1] - 23/07/2019

- Applied specific styles for menus under sidebar-left and sidebar-right

## [2.4.0] - 17/07/2019

- Removed unnecessary files that override styles
- Changed initial configuration of CERN Theme
- Fixed issue of menu overlapping site name [name breaking in 2nd line]
- Added consistent colors in display formats
- Fixed Related Cards component not inheriting colors from theme color palette



## [2.3.0] - 24/04/2019

- Added support for 3rd+ menu level items
- Fixed issue of white line appearing on footer even without block in the 1st column

## [2.2.0] - 26/02/2019

- Added text-shadow on language switcher/site name/slogan
- Increased margin between last menu item. Solves overlapping w/ language switcher

## [2.1.1] - 15/02/2019

- Fixed Preview-list image background overriding any other element


## [2.1.0] - 12/02/2019

- Fixed issue of menu links overflowing out of 2nd level menu box
- Added functionality for expanding 2nd level main menu when hovering
- Removed unnecessary templates
