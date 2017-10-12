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
 * Plugin strings are defined here.
 *
 * @package     qtype_interview
 * @category    string
 * @copyright   2017 Dony Ariesta <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'interview';
$string['pluginnamesummary'] = 'The student need to listen the question and response the answer by recording their voice.';
$string['pluginnameediting'] = 'Editing an Interview question';
$string['pluginnameadding'] = 'Adding an Interview question';
$string['pluginname_help'] = 'You need to record the question and the respondent will listen to the question. The respondent submit the respond by record their voice online. A response template may be provided. Responses must be graded manually.';
$string['label_recordquestion'] = 'Record Question';

$string['responseimmediately'] = 'Response immediately';
$string['responsewhenready'] = 'Response when ready';
$string['response_type'] = 'Response Rule';
$string['response_type_help'] = 'The condition to determine how participant should respond the answer after hearing recorded question.';

$string['repeat_time'] = 'Repeat Question';
$string['repeat_time_help'] = 'How many times the participant can play the recorded question.';
$string['one_time'] = '1 Time';
$string['two_time'] = '2 Times';
$string['three_time'] = '3 Times';
$string['four_time'] = '4 Times';
$string['five_time'] = '5 Times';
$string['no_limit'] = 'No Limit';
$string['play_question_button'] = 'Play Question!';
$string['stop_recording_button'] = 'Stop Recording!';
$string['record_response_button'] = 'Record Response!';

$string['allow_retry_record'] = 'Allow Retry Response';
$string['allow_retry_record_help'] = 'To determine if participant allowed to try record their answer many times or just once.';
$string['yes'] = 'Yes';
$string['no'] = 'No';
