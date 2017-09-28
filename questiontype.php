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
 * Question type class for interview is defined here.
 *
 * @package     qtype_interview
 * @copyright   2017 Dony Ariesta <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/questionlib.php');
require_once(__DIR__ . '/../../../repository/lib.php');

/**
 * Class that represents a interview question type.
 *
 * The class loads, saves and deletes questions of the type interview
 * to and from the database and provides methods to help with editing questions
 * of this type. It can also provide the implementation for import and export
 * in various formats.
 */
class qtype_interview extends question_type {

    // Override functions as necessary from the parent class located at
    // /question/type/questiontype.php.
    public function save_question_options($formdata) {
        global $DB;
        $context = $formdata->context;

        $options = $DB->get_record('qtype_interview_options', array('questionid' => $formdata->id));
        if (!$options) {
            $options = new stdClass();
            $options->questionid = $formdata->id;
            $options->id = $DB->insert_record('qtype_interview_options', $options);
        }

        list($url, $recorder) = json_decode($formdata->recorder_data);
        $options->recorder = $recorder;
        $options->url = $url;
        $options->response_type = $formdata->response_type;
        $options->repeat_time = $formdata->repeat_time;
        $options->allow_retry_record = $formdata->allow_retry_record;
        $DB->update_record('qtype_interview_options', $options);

        $logRecordedFile = $DB->get_record('qtype_interview_log_record', array('recorder' => $recorder));
        if($logRecordedFile){
            $logRecordedFile->is_used = 1;
            $DB->update_record('qtype_interview_log_record', $logRecordedFile);
        }
    }

    public function response_type_options() {
        return array(
            1 => get_string('responseimmediately', 'qtype_interview'),
            0 => get_string('responsewhenready', 'qtype_interview'),
        );
    }

    public function allow_retry_record_options() {
        return array(
            1 => get_string('no', 'qtype_interview'),
            0 => get_string('yes', 'qtype_interview'),
        );
    }

    public function get_question_options($question) {
        global $DB;
        $question->options = $DB->get_record('qtype_interview_options',
                array('questionid' => $question->id), '*', MUST_EXIST);
        parent::get_question_options($question);
    }

    public function delete_question($questionid, $contextid) {
        global $DB;

        $options = $DB->get_record('qtype_interview_options', array('questionid' => $questionid));
        if ($options && $options->recorder != '') {
            $DB->delete_records('qtype_interview_log_record', array('recorder' => $options->recorder));
            repository::delete_tempfile_from_draft(0, '/', $options->recorder);
        }
        $DB->delete_records('qtype_interview_options', array('questionid' => $questionid));
        parent::delete_question($questionid, $contextid);
    }

}
