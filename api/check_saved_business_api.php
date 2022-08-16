<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $user_string = !empty($_GET['user']) ? (string)$_GET['user'] : "";
    $business_string = !empty($_GET['business']) ? (string)$_GET['business'] : "";
    
    $check = SavedBusinesses::find_by_user_business($user_string, $business_string);
    if(!empty($check)){
        $return_array['status'] = 'success';
        $return_array['message'] = 'Business already Saved';
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'Business is not saved';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>