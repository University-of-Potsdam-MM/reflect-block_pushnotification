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
 * Plugin settings
 *
 * @package    block_pushnotification
 * @copyright  2016 Alexander Kiy <alekiy@uni-potsdam.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$settings->add(new admin_setting_heading('sampleheader',
                                         get_string('headerconfig', 'block_pushnotification'),
                                         get_string('descconfig', 'block_pushnotification')));

$settings->add(new admin_setting_configtext('block_pushnotification/URL',
                        get_string('block_pushnotification_url_key', 'block_pushnotification'),
                        get_string('block_pushnotification_url', 'block_pushnotification'), '', PARAM_URL));

$settings->add(new admin_setting_configtextarea('block_pushnotification/headers',
						get_string('block_pushnotification_headers_key', 'block_pushnotification'),
						get_string('block_pushnotification_headers', 'block_pushnotification'), ''));
