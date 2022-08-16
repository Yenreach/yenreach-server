<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $business = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($business)){
        $videolinks = BusinessVideoLinks::find_by_business_string($business);
        if(!empty($videolinks)){
            $data_array = array();
            foreach($videolinks as $link){
                $data_array[] = array(
                        'id' => $link->id,
                        'verify_string' => $link->verify_string,
                        'user_string' => $link->user_string,
                        'business_string' => $link->business_string,
                        'video_link' => $link->video_link,
                        'real_link' => $link->real_link,
                        'platform' => $link->platform,
                        'created' => $link->created,
                        'last_updated' => $link->last_updated
                    );
            }
            $return_array['status'] = 'success';
            $return_array['data'] = $data_array;
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Video Link was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>