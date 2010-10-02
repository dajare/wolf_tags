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

	$filename = dirname(__FILE__) . '/../README.textile';
	$documentation = file_get_contents($filename);
	if ($filter = Filter::get('textile'))
		echo '<div class="wolftags">' . $filter->apply($documentation) . '</div>';
	else
		echo '<pre>' . htmlspecialchars($documentation) .'</pre>';

?>
