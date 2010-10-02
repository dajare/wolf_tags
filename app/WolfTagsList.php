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
 */

include_once(dirname(__FILE__).'/../WolfTagsController.php');

/**
 * Returns an array of all classes derived from $parentClass
 */
function get_sub_classes($parentClass) {
	$subClasses = array();
	foreach(get_declared_classes() as $class) {
		if(is_subclass_of($class, $parentClass)) {
			array_push($subClasses, $class);
		}
	}
	return $subClasses;
}

class WolfTagsList {

	private static $tagList;

	/**
	 * Returns a list of all defined wolf tags. The list is cached in the
	 * private static member variable $tagList.
	 */
	public static function get() {
		try {
			if (!isset(self::$tagList))
				self::set_tag_list();
		}
		catch (Exception $e) {
			$controller = new WolfTagsController();
			$controller->error_page('Fatal Error', $e->getMessage());
		}
		return self::$tagList;
	}

	/**
	 * Gets a list of all defined wolf tags and caches it in the private static
	 * member variable $tagList.
	 */
	private static function set_tag_list() {
		$tagClasses = get_sub_classes('WolfTags');
		$tags = array();
		foreach($tagClasses as $class) {
			$classTags = self::get_tags_from_class($class);
			foreach($classTags as $tag) {
				$tagName = $tag['name'];
				if (isset($tags[$tagName]))
					throw new Exception('Multiple defined tag "' . $tagName . '" in classes "' . $tag['class'] . '" and "' . $tags[$tagName]['class'] . '"!');
			}
			$tags = array_merge($tags, $classTags);
		}
		self::$tagList = $tags;
	}

	/**
	 * Returns an array of all tags defined in $class
	 */
	private static function get_tags_from_class($class) {
		$methods = get_class_methods($class);
		$tags = array();
		foreach ($methods as $method) {
			$name = preg_replace('|^tag_|', '', $method);
			if ($name != $method) {
				$tags[$name] = array('class' => $class, 'method' => $method, 'name' => $name);
			}
		}
		return $tags;
	}

}

?>
