<?php
// var_dump($_FILES);die();
// $fp = fopen( 'savedfile.wav', 'wb' );
// fwrite( $fp, $GLOBALS[ 'HTTP_RAW_POST_DATA' ] );
// fclose( $fp );
require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../../../lib/filelib.php');
require_once(__DIR__ . '/../../../repository/lib.php');



$sql = "SELECT id FROM {repository} WHERE type = 'upload'";
if (!$record = $DB->get_record_sql($sql)) {
    throw new repository_exception('invalidrepositoryid', 'repository');
}

$repo_id   = $record->id;

$contextid = optional_param('ctx_id', SYSCONTEXTID, PARAM_INT); // Context ID
$accepted_types  = optional_param_array('accepted_types', '*', PARAM_RAW);
$maxbytes  = optional_param('maxbytes', 0, PARAM_INT);          // Maxbytes

$repooptions = array(
    'ajax' => true,
    'mimetypes' => $accepted_types
);

$repo = repository::get_repository_by_id($repo_id, $contextid, $repooptions);
list($context) = get_context_info_array($contextid);
// Make sure maxbytes passed is within site filesize limits.
$maxbytes = get_user_max_upload_file_size($context, $CFG->maxbytes, 0, $maxbytes);

if(!empty($_POST['previous'])){
    repository::delete_tempfile_from_draft(0, '/', $_POST['previous']);
    $DB->delete_records("qtype_interview_log_record", array("recorder" => $_POST['previous']));
}
$fileName = 'record_'.date('YmdHis').'.wav';

$result = $repo->upload($fileName, $maxbytes);

//log the uploaded recorded file
$temp = new stdClass();
$temp->recorder = $fileName;
$temp->is_used = 0;
$DB->insert_record('qtype_interview_log_record', $temp);

ajax_check_captured_output();
echo json_encode($result);
