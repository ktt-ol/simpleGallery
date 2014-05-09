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
define("VALID_START", true);

require 'config.php';
require 'functions.php';

//$scriptUrl = $_SERVER["SCRIPT_NAME"];
$scriptUrl = '';

sendNoCacheHeaders();

if (isset($_GET["v"])) {
    // preview page

    $viewUrl = $_GET["v"];

    $lastSlash = strrpos($viewUrl, "/");
    // both without slashes at the begin/end
    $imagePath = substr($viewUrl, 1, $lastSlash -1);
    $imageFile = substr($viewUrl, $lastSlash +1);

    require_once 'pear/Savant3.php';
    $tpl = new Savant3();

    $tpl->title = $imageFile;
    $tpl->previewSize = PREVIEW_SIZE;
    $tpl->fullImageUrl = IMAGES_DIR.$viewUrl;
    $tpl->previewUrl = getSmallSizedImagePath(IMAGES_DIR."/".$imagePath, $imageFile, PREVIEW_SIZE);
    $tpl->display('templates/view.tpl.php');
} else {
    // default (overview) page

    // extra security check for ..
    // die(isset($_GET["p"]));
    $pathInfo = isset($_GET["p"]) ? $_GET["p"] : "";
    if (strpos($pathInfo, '..') !== false) {
        show404("Directory $pathInfo not found.");
        exit();
    }

    // ensure $pathinfo ends with a slash
    if (substr($pathInfo, -1) !== '/') {
        $pathInfo .= "/";
    }

    $folderPath = IMAGES_DIR."$pathInfo";
    if (! is_dir($folderPath)) {
        show404("Directory $pathInfo not found.");
        exit();
    }

    // remove the 'images' prefix
    //$webViewFolderPath = substr($folderPath, strlen(IMAGES_DIR) + 1);

    $imageListForView = array();
    $subFoldersForView = array();

    foreach (scandir($folderPath, 0) as $file) if (validFolderOrFile($file)) {
        if (is_dir("$folderPath/$file")) {
            $subFolder = "$folderPath/$file";
            $folderData = array(
                    "name" => $file,
                    "link" => "$scriptUrl?p=${pathInfo}$file/"
                    );
            // find the first image in that sub folder for preview
            $folderImage = getFirstImage($subFolder);
            if ($folderImage !== null) {
                createPreviewsIfNeeded($subFolder, $folderImage);
                $folderData["thumbImg"] = getSmallSizedImagePath($subFolder, $folderImage, THUMB_SIZE);
            }

            $subFoldersForView[] = $folderData;
    } else {
        if (hasSupportedExtension($file)) {
            createPreviewsIfNeeded($folderPath, $file);
            $imageListForView[] = array(
                    "name" => $file,
                    "link" => "$scriptUrl?v=${pathInfo}$file",
                    "thumbImg" => getSmallSizedImagePath($folderPath, $file, THUMB_SIZE)
                    );
            }
        }
    }

    endGenerating();

    $contentDescription = "";
    $contentFile = "$folderPath/".CONTENT_FILE;
    if (is_file($contentFile)) {
        $contentDescription = file_get_contents($contentFile);
    }

    require_once 'pear/Savant3.php';
    $tpl = new Savant3();

    $tpl->description = $contentDescription;
    $tpl->home = $scriptUrl;
    $tpl->size = THUMB_SIZE;
    $tpl->previewSize = PREVIEW_SIZE;
    $tpl->folders = $subFoldersForView;
    $tpl->images = $imageListForView;

    $parts = explode("/", $pathInfo);
    array_shift($parts);
    array_pop($parts);
    $tpl->title = array_pop($parts);
    $tpl->splittedPath = $parts;
    $tpl->linkUp = "$scriptUrl?p=/".implode("/", $parts);


    $tpl->display('templates/index.tpl.php');
}
?>