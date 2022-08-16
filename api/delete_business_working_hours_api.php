<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $hour = BusinessWorkingHours::find_by_verify_string($string);
        if(!empty($hour)){
            if($hour->delete()){
                $return_array['status'] = 'success';
                $return_array['data'] = array(
                        'id' => $hour->id,
                        'verify_string' => $hour->verify_string,
                        'business_string' => $hour->business_string,
                        'day' => $hour->day,
                        'timing' => $hour->timing,
                        'created' => $hour->created,
                        'last_updated' => $hour->last_updated
                    );
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Business Working Hour Delete Failed';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Business Working Hour was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>