<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $user_string = !empty($_GET['user_string']) ? (string)$_GET['user_string'] : "";
    if(!empty($user_string)){
        $applications = BillboardApplications::find_by_user_string($user_string);
        if(!empty($applications)){
            $return_array['status'] = 'success';
            $return_array['data'] = $applications;
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'There is no Billboard Application for this User';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>