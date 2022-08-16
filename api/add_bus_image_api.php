<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $image = BusinessPhotos::find_by_verify_string($verify_string);
            if(empty($image)){
                $image = new BusinessPhotos();
                $image->verify_string = $verify_string;
                $image->filepath = !empty($post->filepath) ? (string)$post->filepath : "";
                $image->user_string = !empty($post->user_string) ? (string)$post->user_string : "";
                $image->business_string = !empty($post->business_string) ? (string)$post->business_string : "";
                if($image->insert()){
                    $return_array['status'] = 'success';
                    $return_array['message'] = 'Image Details moved successfully';
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $image->errors);
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Image Details already added';
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