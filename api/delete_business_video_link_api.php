<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $link = BusinessVideoLinks::find_by_verify_string($string);
        if(!empty($link)){
            if($link->delete()){
                $return_array['status'] = "success";
                $return_array['data'] = array(
                        'id' => $link->id,
                        'verify_string' => $link->verify_string,
                        'user_string' => $link->user_string,
                        'business_string' => $link->business_string,
                        'real_link' => $link->real_link,
                        'video_link' => $link->video_link,
                        'platform' => $link->platform,
                        'created' => $link->created,
                        'last_updated' => $link->last_updated
                    );
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Video Delete failed';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Video Link was provided';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>