<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $review  = BusinessReviews::find_by_verify_string($verify_string);
            if(!empty($review)){
                $review->review = !empty($post->review) ? (string)$post->review : "";
                $review->star = !empty($post->star) ? (int)$post->star : "";
                if($review->insert()){
                    $return_array['status'] = 'success';
                    $return_array['data'] = $review;
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $review->errors);
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Business review was provided';
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