<?php
/*
 This file is part of sg - Simple Gallery
Copyright (C) 2012 Holger Cremer

sg - Simple Gallery is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

sg is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with sg.  If not, see <http://www.gnu.org/licenses/>.
*/
if (! defined("VALID_START")) die("");

define('THUMB_SIZE', 150);
define('PREVIEW_SIZE', 1000);

/**
 * Containing the full size images and folders.
 */
define('IMAGES_DIR', 'images');

/**
 * All thumbnails and caches are created in this folder. The script needs write permissions in here.
 */
define('DATA_DIR', 'data');

/**
 * Enable this for a performance increase at a cost of memory. The file path information are cached in the session.
 * TODO: implement this feature...
 */
define('ENABLE_SESSION_CACHE', false);

define('CONTENT_FILE', "content.txt");

error_reporting(E_ALL);
ini_set('display_errors', '1');
?>