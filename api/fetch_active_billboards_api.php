<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $time = time();
    $today = strftime("%Y-%m-%d", $time);
    $applications = BillboardApplications::find_period_total($today);
    if(!empty($applications)){
        $return_array['status'] = 'success';
        $return_array['data'] = $applications;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'There are no active applications for now';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>