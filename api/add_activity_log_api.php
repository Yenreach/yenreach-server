<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $log = new ActivityLogs();
        $log->agent_type = !empty($post->agent_type) ? (string)$post->agent_type : "";
        $log->agent_string = !empty($post->agent_string) ? (string)$post->agent_string : "";
        $log->object_type = !empty($post->object_type) ? (string)$post->object_type : "";
        $log->object_string = !empty($post->object_string) ? (string)$post->object_string : "";
        $log->activity = !empty($post->activity) ? (string)$post->activity : "";
        $log->details = !empty($post->details) ? (string)$post->details : "";
        if($log->insert()){
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                    'id' => $log->id,
                    'verify_string' => $log->verify_string,
                    'agent_type' => $log->agent_type,
                    'agent_string' => $log->agent_string,
                    'object_type' => $log->object_type,
                    'object_string' => $log->object_string,
                    'activity' => $log->activity,
                    'details' => $log->details,
                    'created' => $log->created
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'Activity Log was not created';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>