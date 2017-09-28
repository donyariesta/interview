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
 * The interview question renderer class is defined here.
 *
 * @package     qtype_interview
 * @copyright   2017 Dony Ariesta <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Generates the output for interview questions.
 *
 * You should override functions as necessary from the parent class located at
 * /question/type/rendererbase.php.
 */
class qtype_interview_renderer extends qtype_renderer {

    /**
     * Generates the display of the formulation part of the question. This is the
     * area that contains the quetsion text, and the controls for students to
     * input their answers. Some question types also embed bits of feedback, for
     * example ticks and crosses, in this area.
     *
     * @param question_attempt $qa the question attempt to display.
     * @param question_display_options $options controls what should and should not be displayed.
     * @return string HTML fragment.
     */
    public function formulation_and_controls(question_attempt $qa, question_display_options $options) {
        global $DB, $PAGE,$CFG;
        $PAGE->requires->js( new moodle_url($CFG->wwwroot . '/question/type/interview/scripts/response.js') );
        $question = $qa->get_question();

        $inputname = $qa->get_qt_field_name('answer');
        $currentanswer = $qa->get_last_qt_var('answer');

        $uploadURL = new moodle_url('/question/type/interview/upload.php');
        $recorderJS = new moodle_url($CFG->wwwroot . '/question/type/interview/scripts/recorder.js');

        $result = '';
        $result .= html_writer::start_tag('div', array('class' => 'question'));
        $result .= html_writer::tag('p', $question->questiontext);
        $result .= html_writer::start_tag('div', array('class' => 'question-recorder'));

        $options = $DB->get_record('qtype_interview_options', array('questionid' => $question->id));
        if ($options) {
            $result .= html_writer::tag('button', 'Play Question!', array('id'=>'qtypeInterviewQuestionControll', 'data'=>$options->url));
            $result .= html_writer::tag('button', 'Stop Recording!', array('id'=>'qtypeInterviewQuestionStopRecord', 'class'=>'hidden', 'data'=>$uploadURL));
            $result .= html_writer::tag('input', '', array('type'=>'hidden', 'name'=>$inputname,'id'=>'id_recorder_data', 'value'=>$currentanswer));
            $result .= html_writer::tag('div', '', array('id'=>'recordingslist'));
            $result .= '<script type="text/javascript" src="'.$recorderJS.'"></script>';
        }

        $result .= html_writer::end_tag('div');
        $result .= html_writer::end_tag('div');
        $result .= html_writer::start_tag('div', array('class' => 'response'));



        $result .= html_writer::end_tag('div');

        // var_dump($question);die();

        return $result;
    }

    /**
     * Generate the specific feedback. This is feedback that varies according to
     * the response the student gave. This method is only called if the display options
     * allow this to be shown.
     *
     * @param question_attempt $qa the question attempt to display.
     * @return string HTML fragment.
     */
    protected function specific_feedback(question_attempt $qa) {
        return parent::specific_feedback($qa);
    }

    /**
     * Generates an automatic description of the correct response to this question.
     * Not all question types can do this. If it is not possible, this method
     * should just return an empty string.
     *
     * @param question_attempt $qa the question attempt to display.
     * @return string HTML fragment.
     */
    protected function correct_response(question_attempt $qa) {
        return parent::correct_response($qa);
    }

}
