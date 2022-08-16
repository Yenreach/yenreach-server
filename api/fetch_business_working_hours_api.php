<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $hours = BusinessWorkingHours::find_by_business_string($string);
        if(!empty($hours)){
            $data_array = array();
            foreach($hours as $hour){
                $data_array[] = array(
                        'id' => $hour->id,
                        'verify_string' => $hour->verify_string,
                        'business_string' => $hour->business_string,
                        'day' => $hour->day,
                        'timing' => $hour->timing,
                        'opening_time' => $hour->opening_time,
                        'closing_time' => $hour->closing_time,
                        'created' => $hour->created,
                        'last_updated' => $hour->last_updated
                    );
            }
            $return_array['status'] = 'success';
            $return_array['data'] = $data_array;
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Working Hour was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>