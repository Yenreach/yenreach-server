<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $logs = ActivityLogs::find_all();
    if(!empty($logs)){
        $data_array = array();
        foreach($logs as $log){
            if($log->agent_type == "admin"){
            $admin = Admins::find_by_verify_string($log->agent_string);
            $agent_name = $admin->name;
            } else if($log->agent_type == "user"){
                $user = Users::find_by_verify_string($log->agent_string);
                $agent_name = $user->name;
            } else {
                $agent_name = "Unknown";
            }
            $data_array[] = array(
                'id' => $log->id,
                'verify_string' => $log->verify_string,
                'agent_type' => $log->agent_type,
                'agent_string' => $log->agent_string,
                'agent_name' => $agent_name,
                'object_type' => $log->object_type,
                'object_string' => $log->object_string,
                'activity' => $log->activity,
                'details' => $log->details,
                'created' => $log->created
            );
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Activity Log was fetched';
    }

    $result = json_encode($return_array);
    echo $result;
?>