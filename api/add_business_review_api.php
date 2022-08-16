<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $review = new BusinessReviews();
        $review->user_string = !empty($post->user_string) ? (string)$post->user_string : "";
        $review->business_string = !empty($post->business_string) ? (string)$post->business_string : "";
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
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>