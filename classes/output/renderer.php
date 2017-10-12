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
        $readonly = $options->readonly;
        $PAGE->requires->js( new moodle_url($CFG->wwwroot . '/question/type/interview/scripts/main.js') );
        $currentanswer = $qa->get_last_qt_var('answer');
        $dataTrial = 0;
        if(!$readonly){
            $PAGE->requires->js( new moodle_url($CFG->wwwroot . '/question/type/interview/scripts/response.js') );
        }else{
            $PAGE->requires->js( new moodle_url($CFG->wwwroot . '/question/type/interview/scripts/preview.js') );
            if(!empty($currentanswer)){
                $exploded = json_decode($currentanswer);
                if(!empty($exploded[1])){
                    $exploded[0] = (string) moodle_url::make_pluginfile_url(SYSCONTEXTID, 'qtype_interview', 'response', 0, '/', $exploded[1]);
                    $currentanswer = json_encode($exploded);
                }
                $dataTrial = isset($exploded[2]) ? $exploded[2] : 0;
            }

        }
        $question = $qa->get_question();

        $inputname = $qa->get_qt_field_name('answer');

        $uploadURL = new moodle_url('/question/type/interview/upload.php');
        $recorderJS = new moodle_url($CFG->wwwroot . '/question/type/interview/scripts/recorder.js');

        $result = '';
        $result .= html_writer::start_tag('div', array('class' => 'qtypeInterview','data-trial' => $dataTrial));
        $result .= html_writer::tag('p', $question->questiontext);
        $result .= html_writer::start_tag('div', array('class' => 'question'));

        $options = $DB->get_record('qtype_interview_options', array('questionid' => $question->id));
        if ($options) {
            if(($options->repeat_time == 0 || $options->repeat_time > $dataTrial) || $readonly){
                $result .= html_writer::tag('button', get_string('play_question_button', 'qtype_interview'), array('class'=>'playControll', 'data'=>$options->url));
                if(!$readonly && $options->response_type == 0){
                    $result .= html_writer::tag('button', get_string('record_response_button', 'qtype_interview'), array('class'=>'recordControll hidden'));
                }
            }
            if(!$readonly){
                $result .= html_writer::tag('button', get_string('stop_recording_button', 'qtype_interview'), array('class'=>'stopRecord hidden', 'data'=>$uploadURL));
                $recordingURL = new moodle_url('/question/type/interview/pix/recording.gif');
                $result .= '<div class="hidden interview_recording_icon" style="float: left;height: 29px;overflow: hidden;width: 29px;"><img style="margin-top: -110px;margin-left: -110px;width: 250px;" src="'.$recordingURL.'"></div>';
                $result .= '<script type="text/javascript" src="'.$recorderJS.'"></script>';
            }
        }

        $result .= html_writer::end_tag('div');

        $result .= html_writer::start_tag('div', array('class' => 'answer'));
        $result .= html_writer::tag('div', '', array('class'=>'audioWrapper'));
        $result .= html_writer::tag('input', '', array('type'=>'hidden', 'name'=>$inputname,'class'=>'recorder_data', 'value'=>$currentanswer));

        unset($options->id);
        unset($options->questionid);
        unset($options->recorder);
        unset($options->url);
        $result .= html_writer::tag('input', '', array('type'=>'hidden','class'=>'question_options', 'value'=>json_encode((array)$options)));
        $result .= html_writer::end_tag('div');
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
