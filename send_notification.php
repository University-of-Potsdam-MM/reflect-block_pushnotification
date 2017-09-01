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

	//different languages will be sent as separate elements within the URL
	$title_EN = '';
	$title_EN .= $title_en;
	$message_EN = '';
	$message_EN .= $message_en;
	$title_DE = '';
	$title_DE .= $title_de;
	$message_DE = '';
	$message_DE .= $message_de;
	$title_ES = '';
	$title_ES .= $title_es;
	$message_ES = '';
	$message_ES .= $message_es;


	//$url = $endpoint.$operation.'?service='.$service.'&message='.urlencode($json_object_str).'&subscriber=*';
	$short_title= 'ReflectUP';
	$short_message= $course->fullname;

	$url = $endpoint.$operation.'?service='.$service.'&title='.urlencode($short_title).'&msg='.urlencode($short_message).'&message='.urlencode($short_message).'&title_EN='.urlencode($title_EN).'&message_EN='.urlencode($message_EN).'&title_DE='.urlencode($title_DE).'&message_DE='.urlencode($message_DE).'&title_ES='.urlencode($title_ES).'&message_ES='.urlencode($message_ES).'&subscriber=*';

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