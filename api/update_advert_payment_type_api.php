<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $advert = AdvertPaymentTypes::find_by_verify_string($verify_string);
            if(!empty($advert)){
                $advert->title = !empty($post->title) ? (string)$post->title : "";
                $advert->duration_type = !empty($post->duration_type) ? (int)$post->duration_type : "";
                $advert->duration = !empty($post->duration) ? (int)$post->duration : "";
                $advert->amount = !empty($post->amount) ? (double)$post->amount : "";
                if($advert->insert()){
                    $return_array['status'] = 'success';
                    $return_array['data'] = $advert;
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $advert->errors);
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Advert Payment Type was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No means of Identification';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>