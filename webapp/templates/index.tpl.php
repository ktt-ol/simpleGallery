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
<link rel="stylesheet" type="text/css" href="static/lytebox/lytebox.css" />
<link rel="stylesheet" type="text/css" href="static/styling.css" />

<script type="text/javascript" src="static/lytebox/lytebox.js"></script>

<style type="text/css">
.noFolderImage {
	width: <?= $this->size ?>px;
	height: <?= $this->size * 2/3 ?>px;
	background-color: lightYellow;
}
.image .thumb, .grid.image a, .grid .thumb {
    width: <?= $this->size ?>px;
    height: <?= $this->size ?>px;
}
</style>
</head>
<body>

	<div id="indexwrapper">
		<div id="backLink">
			<a href="//www.kreativitaet-trifft-technik.de" class="round">Zurück zur KtT Homepage</a>
		</div>
		<div class="panel round">
			<h1>
				<a href="<?= $this->home ?>">
				    <img src="static//KtT-Logo.png" />
			    </a>
				  <?= $this->title ?>
			</h1>
			<div id="description">
			    <?= $this->description ?>
			</div>
			<div id="breadcrumb">
			<a href="<?= $this->linkUp ?>">
			    |- <b>//</b> -&gt;
			    <? if(count($this->splittedPath) > 0): ?>
    			    <? foreach ($this->splittedPath as $val): ?>
    			        <b><?= $val ?></b> -&gt;
    			    <? endforeach; ?>
			    <? endif; ?>
			</a> <?= $this->title ?>
			</div>
			<!--
			<div id="tips">
			<a href="javascript:void(0)" class="lytetip" data-lyte-options="tipStyle:info tipRelative:true" data-tip="Du kannst in der Vorschau auch mit den Pfeiltasten links/rechts auf der Tastatur navigieren!">
				Tipp!
			</a>
			</div>
			 -->
			<div class="clear"></div>


			<? if (count($this->folders) > 0): ?>
		    <div id="foldercontainer">
		        <div class="grid folder">
    			    <? foreach ($this->folders as $val): ?>
    			    <div class="thumb">
    					<a href="<?= $val['link'] ?>">
    					    <span><?= $val['name'] ?></span>
    					    <? if (isset($val['thumbImg'])): ?>
    					    <img class="shadow" src="<?= $val['thumbImg'] ?>" />
    					    <? else: ?>
    					    <span class="noFolderImage shadow" title="Keine Vorschau verfügbar."> </span>
    					    <? endif; ?>
    					</a>
    				</div>
    			    <? endforeach; ?>
			    </div>
			</div>
			<div class="clear"></div>
			<? endif; ?>

			<div class="grid image">
				<? foreach ($this->images as $val): ?>
				<div class="thumb">
					<a data-lyte-options="group:image scrolling:auto width:100% height:100%" class="lytebox"
						data-title="<?= $val['name'] ?>" href="<?= $val['link'] ?>">
						<img class="shadow" src="<?= $val['thumbImg'] ?>" alt="<?= $val['name'] ?>" />
					</a>
				</div>
				<? endforeach; ?>

			</div>
			<div class="clear"></div>
		</div>
	</div>

	<div class="footer">
		<a href="http://blog.kreativitaet-trifft-technik.de/impressum/">Impressum</a><br />
	</div>
<!--
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="static/jquery-1.7.1.min.js"><\/script>')</script>
 -->
</body>
</html>
