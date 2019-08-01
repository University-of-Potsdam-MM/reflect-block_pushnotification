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
 * Sends notification to push service
 *
 * @package    block_pushnotification
 * @copyright  2017 Alexander Kiy <alekiy@uni-potsdam.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

$courseid = required_param('id', PARAM_INT);
$appkey = required_param('appkey', PARAM_TEXT);
$content = required_param('content', PARAM_TEXT);

require_login();
if (isguestuser()) {
    die();
}

global $COURSE, $PAGE, $USER;

$context = context_course::instance($courseid);

if (has_capability('block/pushnotification:sendnotification', $context)) {

	$endpoint = get_config('block_pushnotification', 'URL');

	$service = 'reflectup-';
	$course = $DB->get_record('course', array('id' => $courseid));

	$service .= $course->idnumber;
	$service = str_replace("-", "", $service);
	$service = strtolower($service);

	$headers = explode("\n", str_replace("\r", "",get_config('block_pushnotification', 'headers')));
	//print_r($headers);

	// append X-AN-APP-NAME to headers
	array_push($headers, "X-AN-APP-NAME: ".$service);

	// append X-AN-APP-KEY from configuration to headers
	array_push($headers, "X-AN-APP-KEY: ".$appkey);

	// new HTTP-POST-Request
	$body = array(
				"alert" => $content,
				"sound" => "Submarine.aiff",
				"badge" => 1,
				"apns" => array(
						"content" => 1,
						"sound" => "Submarine.aiff",
						"badge" => 1
				));
	$data_string = json_encode($body);

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt_array($curl, array(
									CURLOPT_CUSTOMREQUEST => "POST",
									CURLOPT_POSTFIELDS => $data_string,
									CURLOPT_RETURNTRANSFER => 1,
									CURLOPT_HTTPHEADER => $headers,
									CURLOPT_URL => $endpoint
									));

	$result = curl_exec($curl);

	curl_close($curl);

	$new_message = new stdClass();
	$new_message->idnumber = $course->idnumber;
	$new_message->userid = $USER->id;
	$new_message->timestamp = time();
	// $new_message->title
	$new_message->message = $content;

	$DB->insert_record('block_pushnotification', $new_message);

	$courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
	redirect($courseurl);

} else {
	throw new moodle_exception('missingrequiredcapability', 'block/pushnotification:sendnotification');
}