<?
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
?>
<? if (! defined("VALID_START")) die(""); ?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?= $this->title ?></title>
<link rel="stylesheet" type="text/css"
	href="static/styling.css" />
</head>
<body id="view">
	<a href="<?= $this->fullImageUrl ?>" target="_top"> <img
		src="<?= $this->previewUrl ?>"
		title="Klicke HIER für die original Bildgröße" />
	</a>
</body>
</html>
