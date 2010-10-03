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

if (!defined('WolfTagsControllerIncluded')) {

	define('WolfTagsControllerIncluded', true);

	include_once('app/WolfTagsDocumentation.php');

	class WolfTagsController extends PluginController {

		public function __construct() {
			AuthUser::load();
			if (!AuthUser::isLoggedIn())
				redirect(get_url('login'));
			$this->setLayout('backend');
			$this->assignToLayout('sidebar', new View('../../plugins/wolf_tags/views/sidebar'));
		}
		
		public function documentation() {
			$this->display('wolf_tags/views/documentation');
		}

		public function available_tags() {
			$this->display('wolf_tags/views/available_tags');
		}

		public function sample_layout() {
			$this->display('wolf_tags/views/sample_layout');
		}

		public function error_page($title, $message) {
			$this->setLayout(false);
			$this->display('wolf_tags/views/error_page', array('title' => $title, 'message' => $message), true);
		}
	}

}

?>
