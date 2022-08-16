<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $saveds = SavedBusinesses::find_by_user_string($string);
        if(!empty($saveds)){
            $data_array = array();
            foreach($saveds as $saved){
                $job = Businesses::find_by_verify_string($saved->business_string);
                if(!empty($job)){
                    $photos = BusinessPhotos::find_by_business_string($job->verify_string);
                    $categories = BusinessCategories::find_by_business_string($job->verify_string);
                    $user = Users::find_by_verify_string($string);
                    $job->categories = $categories;
                    $job->photos = $photos;
                    $job->user_string = $job->user_string;
                    $job->user_email = $user->email;
                    $job->user_name = $user->name;
                    $job->photos = $photos;
                    
                    $data_array[] = $job;
                }
            }
            if(!empty($data_array)){
                $return_array['status'] = 'success';
                $return_array['data'] = $data_array;
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Business was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['status'] = 'No Saved Businesses';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>