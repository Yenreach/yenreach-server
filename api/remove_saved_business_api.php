<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $user_string = !empty($_GET['user_string']) ? (string)$_GET['user_string'] : "";
    $business_string = !empty($_GET['business_string']) ? (string)$_GET['business_string'] : "";
    
    if(!empty($user_string) && !empty($business_string)){
        $saved = SavedBusinesses::find_by_user_business($user_string, $business_string);
        if(!empty($saved)){
            if($saved->delete()){
                $return_array['status'] = 'success';
                $return_array['data'] = $saved;
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Removal of Business from Saved Businesses List failed';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Saved Business was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>