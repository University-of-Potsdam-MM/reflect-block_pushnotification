<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Manage files in folder in private area - to be replaced by something better hopefully....
 *
 * @package   block_private_files
 * @copyright 2010 Petr Skoda (http://skodak.org)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

$courseid = required_param('id', PARAM_INT);
$title = required_param('title', PARAM_TEXT);
$message = required_param('msg', PARAM_TEXT);

require_login();
if (isguestuser()) {
    die();
}

$courseurl = new moodle_url('/course/view.php', array('id' => $courseid));

$endpoint = 'http://api.uni-potsdam.de/endpoints/pushAPI/';
$operation = 'push';
$service = 'reflectup';

$url = $endpoint.$operation.'?service='.$service.'&message='.urlencode($message).'&title='.urlencode($title).'&subscriber=*';
var_dump($url);
$headers = array(
				"Authorization:"."Bearer c06156e119040a27a4b43fa933f130"
				);

// new HTTP-Request
$curl = curl_init();
curl_setopt_array($curl, array(
								CURLOPT_RETURNTRANSFER => 1,
								CURLOPT_HTTPHEADER => $headers,
								CURLOPT_URL => $url
								));
$result = curl_exec($curl);
curl_close($curl);

redirect($courseurl);