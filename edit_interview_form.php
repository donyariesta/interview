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
 * The editing form for interview question type is defined here.
 *
 * @package     qtype_interview
 * @copyright   2017 Dony Ariesta <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * interview question editing form defition.
 *
 * You should override functions as necessary from the parent class located at
 * /question/type/edit_question_form.php.
 */
class qtype_interview_edit_form extends question_edit_form {

    /**
     * Add question-type specific form fields.
     *
     * @param object $mform the form being built.
     */
    protected function definition_inner($mform) {
        global $PAGE, $CFG;

        $qtype = question_bank::get_qtype('interview');

        $PAGE->requires->js('/question/type/interview/scripts/recorder.js',true);
        $PAGE->requires->js('/question/type/interview/scripts/interview.js',true);
        $uploadURL = new moodle_url('/question/type/interview/upload.php');
        $recordingURL = new moodle_url('/question/type/interview/pix/recording.gif');
        // $uploadURL = './type/interview/upload.php';

        $element = '<button onclick="startRecording(this);">record</button>';
        $element .= '<button class="hidden" onclick="stopRecording(this,\''.$uploadURL.'\');" disabled>stop</button>';
        $element .= '<div id="interview_recording_icon" class="hidden" style="float: left;height: 29px;overflow: hidden;width: 29px;"><img style="margin-top: -110px;margin-left: -110px;width: 250px;" src="'.$recordingURL.'"></div>';
        $element .= '<div id="recordingslist"></div>';

        $mform->addElement('static', 'recorder',get_string('label_recordquestion', 'qtype_interview'), $element);
        $mform->addElement('hidden', 'recorder_data', '', array('id' => 'id_recorder_data'));
        $mform->setType('recorder_data', PARAM_RAW);

        $mform->addElement('select', 'response_type',
                get_string('response_type', 'qtype_interview'), $qtype->response_type_options());
        $mform->setDefault('response_type', 1);
        $mform->addHelpButton('response_type', 'response_type', 'qtype_interview');
        $mform->setType('response_type', PARAM_INT);

        $mform->addElement('text', 'repeat_time', get_string('repeat_time', 'qtype_interview'),
                array('size' => 2));
        $mform->setType('repeat_time', PARAM_INT);
        $mform->setDefault('repeat_time', 1);
        $mform->addHelpButton('repeat_time', 'repeat_time', 'qtype_interview');

        $mform->addElement('select', 'allow_retry_record',
                get_string('allow_retry_record', 'qtype_interview'), $qtype->allow_retry_record_options());
        $mform->setDefault('allow_retry_record', 0);
        $mform->addHelpButton('allow_retry_record', 'allow_retry_record', 'qtype_interview');
        $mform->setType('allow_retry_record', PARAM_INT);

    }

    protected function data_preprocessing($question) {
        $question = parent::data_preprocessing($question);

        if (empty($question->options)) {
            return $question;
        }

        $question->recorder_data = json_encode([$question->options->url, $question->options->recorder]);
        $question->response_type = $question->options->response_type;
        $question->repeat_time = $question->options->repeat_time;
        $question->allow_retry_record = $question->options->allow_retry_record;

        return $question;
    }


    /**
     * Returns the question type name.
     *
     * @return string The question type name.
     */
    public function qtype() {
        return 'interview';
    }
}
