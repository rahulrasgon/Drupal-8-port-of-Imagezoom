## Overview

This module provides an field formatter to zoom an image while hovering over
it. An image style is selected for the default display image, and an additional
style is selected to be used as the zoomed image. When a user hovers over the
displayed image, the zoomed image appears and is positioned relative to the
current mouse position.


## Requirements

This module depends on the core Image module and Libraries API being enabled.
The elevatezoom jQuery plugin is used for the zoom effects.


## Install

### Bower method:

If you have bower installed you can change directory into the imagezoom module directory and run `bower install`. This will create a `libraries` directory which you _must_ move to your Drupal root.

### Manual method:

The only library required by this module is the [elevatezoom-plus](https://github.com/igorlino/elevatezoom-plus) plugin. You need to download the jQuery version and place it in `/libraries` in your docroot (create the directory if you need to). Rename your newly placed download to ez-plus, so the resulting path is `/libraries/ez-plus`.

## Configuration

To configure the Image Zoom display, go to Administration > Structure > Content
types and select the content type you would want to use Image Zoom with. If you
do not already have an Image field defined, add one by going to the Manage Fields
tab. After you have an Image field, go to the Manage Display tab. Change the
Format for your Image field to Image Zoom. To change which styles are displayed
for the displayed image and the zoomed image, click the gear icon at the end of
the row, select the desired image styles, and click Update.
