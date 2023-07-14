<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    $skip = !empty($_GET['offset']) ? (int)$_GET['skip'] : 0;
    $per_page = !empty($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
    
    $businesses = Businesses::find_approved_business_paginated($per_page, $skip);
    if(!empty($businesses)){
        $data_array = array();
        foreach($businesses as $business){
            $user = Users::find_by_verify_string($business->user_string);
            $photos = BusinessPhotos::find_by_business_string($business->verify_string);
            $categories = BusinessCategories::find_by_business_string($business->verify_string);
            $reviews = BusinessReviews::find_by_business_string($business->verify_string);

            $data_array[] = array(
                    'id' => $business->id,
                    'verify_string' => $business->verify_string,
                    'name' => $business->name,
                    'description' => $business->description,
                    'user_string' => $business->user_string,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'subscription_string' => $business->subscription_string,
                    'category' => $business->category, 
                    'address' => $business->address,
                    'town' => $business->town,
                    'lga' => $business->lga,
                    'state' => $business->state,
                    'state_id' => $business->state_id,
                    'phonenumber' => $business->phonenumber,
                    'whatsapp' => $business->whatsapp,
                    'email' => $business->email,
                    'website' => $business->website,
                    'facebook_link' => $business->facebook_link,
                    'twitter_link' => $business->twitter_link,
                    'instagram_link' => $business->instagram_link,
                    'youtube_link' => $business->youtube_link,
                    'linkedin_link' => $business->linkedin_link,
                    'working_hours' => $business->working_hours,
                    'cv' => $business->cv,
                    'modifiedby' => $business->modifiedby,
                    'experience' => $business->experience,
                    'month_started' => $business->month_started,
                    'year_started' => $business->year_started,
                    'reg_stage' => $business->reg_stage,
                    'activation' => $business->activation,
                    'filename' => $business->filename,
                    'remarks' => $business->remarks,
                    'created' => $business->created,
                    'last_updated' => $business->last_updated,
                    'photos' => $photos,
                    'reviews' => $reviews,
                    'categories' => $categories
                );
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Approved Business was fetched';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>