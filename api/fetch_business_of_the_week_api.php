<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $week = BusinessWeek::find_week_business();
    if(!empty($week)){
        $string = $week->business_string;
        $business = Businesses::find_by_verify_string($string);
        if(!empty($business)){
            $user = Users::find_by_verify_string($business->user_string);
            $photos = BusinessPhotos::find_by_business_string($business->verify_string);
            $categories = BusinessCategories::find_by_business_string($business->verify_string);
            $return_array['status'] = 'success';
            $return_array['data'] = array(
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
                    'categories' => $categories
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Business was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Business for this week';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>