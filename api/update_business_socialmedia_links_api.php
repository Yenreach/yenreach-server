<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $business = Businesses::find_by_verify_string($verify_string);
            if(!empty($business)){
                $business->facebook_link = !empty($post->facebook_link) ? (string)$post->facebook_link : "";
                $business->twitter_link = !empty($post->twitter_link) ? (string)$post->twitter_link : "";
                $business->instagram_link = !empty($post->instagram_link) ? (string)$post->instagram_link : "";
                $business->linkedin_link = !empty($post->linkedin_link) ? (string)$post->linkedin_link : "";
                $business->youtube_link = !empty($post->youtube_link) ? (string)$post->youtube_link : "";
                $business->whatsapp = !empty($post->whatsapp) ? (string)$post->whatsapp : "";
                $business->website = !empty($post->website) ? (string)$post->website : "";
                
                if($business->insert()){
                    $return_array['status'] = 'success';
                    $return_array['data'] = array(
                            'id' => $business->id, 
                            'verify_string' => $business->verify_string, 
                            'name' => $business->name, 
                            'description' => $business->description,
                            'user_string' => $business->user_string, 
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
                            'created' => $business->created,
                            'last_updated' => $business->last_updated
                        ); 
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $business->errors);
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Business was fetched';
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