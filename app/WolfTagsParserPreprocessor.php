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

class WolfTagsParserPreprocessor {

	/**
	 * Runs the preprocessor. The preprocessor has two assignments.
	 *
	 * First it replaces empty tags that correspond to the pattern
	 * <w:name ... /> with <w:name ...></w:name>.
	 *
	 * Secondly it marks top-level tags with sign '@' before the tag's name.
	 * Top-level tags are tags that are not surrounded by other tags. As the
	 * tag parser will only parse marked tags it will only parse top-level
	 * tags.
	 *
	 * The content of the top-level tags can afterwards be parsed inside of the
	 * tag definition using the method 'expand' of class WolfTags.
	 */
	public function run($string) {
		try {
			// replace empty tags
			$string = preg_replace("|<w:(\w+?)([^<>]*)/>|U", "<w:\\1\\2></w:\\1>", $string);

			// replace nested tags (e. g. <w:children:each> with <w:children><w:each>)
			$string = preg_replace_callback("|<(/?)w:([:\w]+?)([^<>]*)>|U", array($this, 'replace_nested_tag'), $string);

			// mark top-level tags
			$this->parentTags = array();
			$string = preg_replace_callback("|<(/?)w:(\w+?)([^<>]*)>|U", array($this, 'process_tag'), $string);
		}
		catch (Exception $e) {
			$controller = new WolfTagsController();
			$controller->error_page('Fatal Parsing Error', $e->getMessage());
		}
		return $string;
	}

	/**
	 * Replaces nested tags.
	 *
	 * For example <w:children:each status="all"> will be replaced with
	 * <w:children status="all"><w:each status="all">. Closing tags as
	 * </w:children:each> will be replaced with </w:each></w:children>.
	*/
	public function replace_nested_tag($matches) {
		if (substr_count($matches[2], ':') <= 0) {
			return $matches[0];
		}
		else {
			$tags = explode(':', $matches[2]);
			if($matches[1] == '/') {
				$tags = array_reverse($tags);
			}
			$result = '';
			foreach($tags as $name) {
				if(!empty($name)) {
					$prefix    = $matches[1];
					$arguments = $matches[3];
					$result .= '<' . $prefix . 'w:' . $name . $arguments . '>';
				}
			}
			return $result;
		}
	}

	/**
	 * Marks top-level tags with sign '@' before the tag's name.
	 */
	private function process_tag($matches) {
		$prefix    = $matches[1];
		$name      = $matches[2];
		$arguments = $matches[3];
		if ($prefix == '')
			$this->open_tag($name);
		elseif($prefix == '/')
			$this->close_tag($name);
		else
			throw new Exception('Unknown prefix in <code>' . htmlspecialchars($matches[0]) . '</code>!');
		return '<' . $prefix . 'w:' . $name . $arguments . '>';
	}

	/**
	 * Array to store all the opened tags (in order of appearance).
	 */
	private $parentTags;

	/**
	 * Marks a start tag if it is a top-level tag.
	 */
	private function open_tag(&$name) {
		array_push($this->parentTags, $name);

		// mark start tag if it is a top-level tag
		if (count($this->parentTags) == 1)
			$name = '@'.$name;
	}

	/**
	 * Marks an end tag if it is a top-level tag.
	 */
	private function close_tag(&$name) {
		if (!in_array($name, $this->parentTags)) {
			throw new Exception("End tag for element 'w:$name' which is not open!");
		}
		if (end($this->parentTags) != $name)
			throw new Exception("Expected end tag for element 'w:" . end($this->parentTags) . "' but got end tag for element 'w:$name'!");
		array_pop($this->parentTags);

		// mark end tag if it is a top-level tag
		if (count($this->parentTags) == 0)
			$name = '@'.$name;
	}

}

?>
