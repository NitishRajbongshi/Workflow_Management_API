<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once "../../../config/Database.php";
include_once "../../../model/Workflow.php";

// instance of Database class
$database = new Database();
$db = $database->connect();

// instance of Workflow class
$workflow = new Workflow($db);

// get raw id to fetch single workflow
$workflow->workflow_id = isset($_GET['id']) ? $_GET['id'] : die();

$result = $workflow->get_workflow_and_step();
$num_of_records = $result->rowCount();

if ($num_of_records > 0) {
    $workflow = array();
    $workflow_array = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        if(empty($workflow)) {
            $workflow = array(
                "workflow_id" => $workflow_id,
                "workflow_title" => $workflow_name,
                "workflow_description" => $workflow_description,
                "created_at" => $created_at
            );
        }

        $items = array(
            "step_id" => $step_id,
            "workflow_id" => $workflow_id,
            "step_name" => $step_name,
            "step_order" => $step_order,
            "step_type" => $step_type,
            "step_handleby" => $step_handleby
        );

        // push data to the array
        array_push($workflow_array, $items);
    }
    // convert to json
    echo json_encode($workflow);
    echo json_encode($workflow_array);
} else {
    echo json_encode(
        array("Message" => "No workflow found")
    );
}
