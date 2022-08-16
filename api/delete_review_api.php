<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $review = BusinessReviews::find_by_verify_string($string);
        if(!empty($review)){
            if($review->delete()){
                $return_array['status'] = 'success';
                $return_array['data'] = $review;
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Business Review was not deleted';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Review was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>