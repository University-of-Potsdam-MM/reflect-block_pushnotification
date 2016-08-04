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
 * Pushnotification Block
 *
 * @package    block_pushnotification
 * @copyright  Alexander Kiy
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_pushnotification extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_pushnotification');
    }

    function get_content() {
        global $DB, $CFG, $OUTPUT, $COURSE;

        if ($this->content !== null) {
            return $this->content;
        }

		$course = $DB->get_record('course', array('idnumber'=> $this->page->course->idnumber));
        $context = context_course::instance($COURSE->id);

        if (empty($this->instance) || empty($course) || !has_capability('block/pushnotification:sendnotification', $context)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        $this->content->text  = '<div class="searchform" style="text-align:center;">';
        $this->content->text .= '<form action="'.$CFG->wwwroot.'/blocks/pushnotification/send_notification.php" style="display:inline"><fieldset class="invisiblefieldset">';
        $this->content->text .= '<input name="id" type="hidden" value="'.$this->page->course->id.'" />';  // course
		$this->content->text .= '<label class="accesshide" for="pushform_title">'.get_string('title', 'block_pushnotification').'</label>'.
                                '<input id="pushform_title" name="title" type="text" size="20" placeholder="'.get_string('title', 'block_pushnotification').'" />';
        $this->content->text .= '<label class="accesshide" for="pushform_msg">'.get_string('content', 'block_pushnotification').'</label>'.
                                '<textarea id="pushform_msg" name="msg" rows="4" cols="22" placeholder="'.get_string('text', 'block_pushnotification').'"></textarea>';
        $this->content->text .= '<button id="searchform_button" type="submit">'.get_string('submit').'</button><br />';
        $this->content->text .= '</fieldset></form></div>';

        return $this->content;
    }

    // my moodle can only have SITEID and it's redundant here, so take it away
    public function applicable_formats() {
        return array('all' => false,
                     'course-view' => true);
    }

    public function instance_allow_multiple() {
          return false;
    }

    function has_config() {
            return true;
    }
}
