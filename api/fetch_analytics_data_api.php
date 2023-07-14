<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $business_count = Businesses::count_all();
    $user_count = Users::count_all();
    if(!empty($business_count)){
        $return_array['status'] = 'success';
        $return_array['data'] = array(
            'business_count' => $business_count,
            'user_count' => $user_count
        );
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Business was fetched';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>