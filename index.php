<?php

/**
 * Copyright 2009-2010 Bastian Harendt <b.harendt@gmail.com>
 * Copyright 2010- David Reimer <dajare@gmail.com>
 *
 * This file is part of WolfTags Plugin.
 *
 * WolfTags Plugin is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option)
 * any later version.
 *
 * WolfTags Plugin is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * WolfTags Plugin.  If not, see <http://www.gnu.org/licenses/>.
 *
 * WolfTags Plugin is a port of Bastian Harendt's FrogTags, designed for Frog CMS 0.9.5.
 * For full history see changelog.
 */

if (!defined('WolfTagsPluginIncluded')) {

	define('WolfTagsPluginIncluded', true);

	Plugin::setInfos(array(
		'id'          => 'wolf_tags',
		'title'       => 'Wolf Tags',
		'description' => 'Allows defining and using of HTML-like tags, called Wolf tags.',
		'version'     => '0.0.1', 
		'license'     => 'GPL3',
		'author'      => 'Bastian Harendt, David Reimer',
		'website'     => 'http://github.com/dajare/wolf_tags/',
		'update_url'  => 'http://github.com/dajare/wolf_tags/raw/master/version.xml',
		'require_wolf_version' => '0.5.5'
	));

	Plugin::addController('wolf_tags', 'Wolf Tags', '', false);

	// default setting (could be changed in WOLF_ROOT/config.php)
	if (!defined('ALLOW_PHP')) define('ALLOW_PHP', false);

	include_once('app/WolfTagsParser.php');
	include_once('app/StandardTags.php');
	include_once('app/WolfTagsHacks.php');

	function wolf_tags_main($page) {
		$content = WolfTagsHacks::get_page_layout($page);
		$parser = new WolfTagsParser($page);
		$content = $parser->parse($content);
		echo $content;
	}

}

?>
