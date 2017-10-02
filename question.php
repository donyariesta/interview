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
 * Question definition class for interview.
 *
 * @package     qtype_interview
 * @copyright   2017 Dony Ariesta <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// For a complete list of base question classes please examine the file
// /question/type/questionbase.php.
//
// Make sure to implement all the abstract methods of the base class.

/**
 * Class that represents a interview question.
 */
class qtype_interview_question extends question_with_responses {


    public function make_behaviour(question_attempt $qa, $preferredbehaviour) {
        return question_engine::make_behaviour('manualgraded', $qa, $preferredbehaviour);
    }

    /**
     * @param moodle_page the page we are outputting to.
     * @return qtype_essay_format_renderer_base the response-format-specific renderer.
     */
    public function get_format_renderer(moodle_page $page) {
        var_dump('asdas');die();
        return $page->get_renderer('qtype_interview', 'format_' . $this->responseformat);
    }


    /**
     * Returns data to be included in the form submission.
     *
     * @return array|string.
     */
    public function get_expected_data() {
        $expecteddata = array('answer' => PARAM_RAW);
        return $expecteddata;
    }

    /**
     * Returns the data that would need to be submitted to get a correct answer.
     *
     * @return array|null Null if it is not possible to compute a correct response.
     */
    public function get_correct_response() {
        $response = parent::get_correct_response();
        var_dump($response);die('get_correct_response');
        return null;
    }

    /**
     * Checks whether the user is allowed to be served a particular file.
     *
     * @param question_attempt $qa The question attempt being displayed.
     * @param question_display_options $options The options that control display of the question.
     * @param string $component The name of the component we are serving files for.
     * @param string $filearea The name of the file area.
     * @param array $args the Remaining bits of the file path.
     * @param bool $forcedownload Whether the user must be forced to download the file.
     * @return bool True if the user can access this file.
     */
    public function check_file_access($qa, $options, $component, $filearea, $args, $forcedownload) {
        return parent::check_file_access($qa, $options, $component, $filearea, $args, $forcedownload);
    }

    public function is_complete_response(array $response) {
        return array_key_exists('answer', $response) && ($response['answer'] !== '');
    }

    public function is_same_response(array $old, array $new) {
        $value1 = isset($old['answer']) ? (string) $old['answer'] : '';
        $value2 = isset($new['answer']) ? (string) $new['answer'] : '';
        return $value1 === $value2;
    }

    public function summarise_response(array $response) {
        file_save_draft_area_files(0, SYSCONTEXTID, 'qtype_interview', 'response', 0, []);
        return isset($response['answer']) ? $response['answer']: null;
    }
}
