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

function sendNoCacheHeaders() {
    header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
}

function createPreviewsIfNeeded($folderPath, $imageFile) {
    $sourceImageFile = $folderPath ."/".$imageFile;
    $thumbnailFile = getSmallSizedImagePath($folderPath, $imageFile, THUMB_SIZE);
    if (! is_file($thumbnailFile)) {
        createImage($sourceImageFile, $thumbnailFile, THUMB_SIZE);
    }

    $previewFile = getSmallSizedImagePath($folderPath, $imageFile, PREVIEW_SIZE);
    if (! is_file($previewFile)) {
        createImage($sourceImageFile, $previewFile, PREVIEW_SIZE);
    }
}

function createImage($sourceImageFile, $thumbnailFile, $size) {
	beginGenerating();
	# reset script time limit to 20s (wont work in safe mode)
	set_time_limit(120);
	$ext = strtolower(pathinfo($sourceImageFile, PATHINFO_EXTENSION));
	$isJpg;
	switch ($ext) {
		case "jpg":
		case "jpeg":
			$isJpg = true;
			break;
		case "png":
			$isJpg = false;
			break;
		default:
		die("unknown extension: ".$ext);
	}
	if ($isJpg) {
		$img = imagecreatefromjpeg($sourceImageFile);
	} else {
		$img = imagecreatefrompng($sourceImageFile);
	}

	$w = imagesx($img);
	$h = imagesy($img);

	# uncomment this if you need group writable files
	#umask(0002);

	# create the thumbs directory recursively
	if (! is_dir(dirname($thumbnailFile))) {
		mkdir(dirname($thumbnailFile), 0777, true);
	}

	if ($w > $h) {
		$newW = $size;
		$newH = $h/($w/$size);
	} else {
		$newW = $w/($h/$size);
		$newH = $size;
	}

	$newImg = imagecreatetruecolor($newW, $newH);

	imagecopyresampled($newImg, $img, 0, 0, 0, 0, $newW, $newH, $w, $h);

	if ($isJpg) {
		imagejpeg($newImg, $thumbnailFile);
	} else {
		imagepng($newImg, $thumbnailFile);
	}

	imagedestroy($img);
	imagedestroy($newImg);

	displayGenerated($thumbnailFile);
}

function getSmallSizedImagePath($folderPath, $imageFile, $size) {
    return sprintf("%s/%s/%d_%s", DATA_DIR, $folderPath, $size, $imageFile);
}

function show404($msg = "Not found") {
    header("HTTP/1.1 404 Not Found");
    die($msg);
}

// functions to display a "progress page" when thumbs are generating
function beginGenerating ()
{
	if (! isset($GLOBALS["generating"])) {
		echo <<<END
<!doctype html>
<html><head><meta charset="utf-8"><title>Generating previews...</title></head>
<body><h1>Generating previews...</h1>
<p>Please wait while I'm generating the previews for you. This can take some time.</p>
<p><i><b>If you get: "Fatal error: Maximum execution time exceeded", refresh this page.</b></i></p>
<hr /> Finished the following files:<div style="font-family: monospace;">
END;
		ob_flush(); flush();
		$GLOBALS["generating"] = true;
	}
}

function displayGenerated($previewFile)
{
	if (isset($GLOBALS["generating"])) {
		echo basename($previewFile)." ★  ";
		ob_flush(); flush();
	}
}

function endGenerating() {
	if (isset($GLOBALS["generating"])) {
		echo <<<END
</div><p>All files Finished. This page will be refreshed.</p><script>window.location.reload();</script>
</body></html>
END;
		exit();
	}
}

/**
 *
 * @param unknown_type $source
 * @param unknown_type $char
 */
function stripLastCharIfMatches($source, $char) {
    $len = strlen($source);
    if ($len !== 0) {
        if (substr($source, -1) === $char) {
            return substr($source, 0, $len -1);
        }
    }
    return $source;
}

/**
 * 'true' if the given files is a jpeg, jpg or a png.
 * @param unknown_type $file
 */
function hasSupportedExtension($file) {
	$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
	return ($ext === "jpg" || $ext === "jpeg" || $ext === "png");
}

function getFirstImage($dir)
{
	foreach (scandir($dir) as $subFile) if ($subFile !== '.' and $subFile !== '..') {
		if (hasSupportedExtension($subFile)) {
			return $subFile;
		}
	}

	return null;
}

?>