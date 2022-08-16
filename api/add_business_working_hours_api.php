<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $hour = new BusinessWorkingHours();
        $hour->business_string = !empty($post->business_string) ? (string)$post->business_string : "";
        $hour->day = !empty($post->day) ? (string)$post->day : "";
        $hour->opening_time = !empty($post->opening_time) ? (string)$post->opening_time : "";
        $hour->closing_time = !empty($post->closing_time) ? (string)$post->closing_time : "";
        
        if($hour->insert()){
            $return_array['status'] = 'success';
            $return_array['data'] = array(
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
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = join(' ', $hour->errors);
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>