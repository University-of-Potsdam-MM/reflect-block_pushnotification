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
$title_en = required_param('title_en', PARAM_TEXT);
$title_de = required_param('title_de', PARAM_TEXT);
$title_es = required_param('title_es', PARAM_TEXT);
$message_en = required_param('msg_en', PARAM_TEXT);
$message_de = required_param('msg_de', PARAM_TEXT);
$message_es = required_param('msg_es', PARAM_TEXT);

require_login();
if (isguestuser()) {
    die();
}

global $COURSE, $PAGE;

$context = context_course::instance($COURSE->id);

if (has_capability('block/pushnotification:sendnotification', $context)){

	$endpoint = get_config('block_pushnotification', 'URL');

	$operation = 'push';
	$service = 'reflectup-';
  $course = $DB->get_record('course', array('id' => $courseid));

	$service .= $course->idnumber;

	//give the correct formating to user's input
	$json_object_str= '{';
	$json_object_str .= '"en":{';
	$json_object_str .= '"title":"';
	$json_object_str .= $title_en;
	$json_object_str .= '",';
	$json_object_str .= '"message":"';
	$json_object_str .= $message_en;
	$json_object_str .= '"},';
	$json_object_str .= '"de":{';
	$json_object_str .= '"title":"';
	$json_object_str .= $title_de;
	$json_object_str .= '",';
	$json_object_str .= '"message":"';
	$json_object_str .= $message_de;
	$json_object_str .= '"},';
	$json_object_str .= '"es":{';
	$json_object_str .= '"title":"';
	$json_object_str .= $title_es;
	$json_object_str .= '",';
	$json_object_str .= '"message":"';
	$json_object_str .= $message_es;
	$json_object_str .= '"}}';


	$url = $endpoint.$operation.'?service='.$service.'&message='.urlencode($json_object_str).'&subscriber=*';
	$short_message= $course->fullname;

	$url = $endpoint.$operation.'?service='.$service.'&message='.urlencode($json_object_str).'&msg='.urlencode($short_message).'&subscriber=*';

	$headers = explode("\n", str_replace("\r", "",get_config('block_pushnotification', 'headers')));

	// new HTTP-Request

	$curl = curl_init();
	curl_setopt_array($curl, array(
									CURLOPT_RETURNTRANSFER => 1,
									CURLOPT_HTTPHEADER => $headers,
									CURLOPT_URL => $url
									));
	$result = curl_exec($curl);
	curl_close($curl);

	$courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
	redirect($courseurl);

}