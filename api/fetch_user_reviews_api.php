<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $reviews = BusinessReviews::find_by_user_string($string);
        if(!empty($reviews)){
            $data_array = array();
            foreach($reviews as $review){
                $business = Businesses::find_by_verify_string($review->business_string);
                $review->business = $business->name;
                $review->business_email = $business->email;
                $data_array[] = $review;
            }
            $return_array['status'] = 'success';
            $return_array['data'] = $data_array;
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Review has been fetched for this Business';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>