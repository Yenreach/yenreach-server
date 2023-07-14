<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $business = new Businesses();
        $business->name = !empty($post->name) ? (string)$post->name : "";
        $business->user_string = !empty($post->user_string) ? (string)$post->user_string : "";
        $business->description = !empty($post->description) ? (string)$post->description : "";
        $business->phonenumber = !empty($post->phone) ? (string)$post->phone : "";
        $business->email = !empty($post->email) ? (string)$post->email : "";
        $business->address = !empty($post->address) ? (string)$post->address : "";
        $business->town = !empty($post->town) ? (string)$post->town : "";
        $business->lga = !empty($post->lga) ? (string)$post->lga : "";
        $business->state_id = !empty($post->state_id) ? (string)$post->state_id : "";
        $state = States::find_by_id($business->state_id);
        $business->state = $state->name;
        $business->month_started = !empty($post->month_started) ? (string)$post->month_started : "";
        $business->year_started = !empty($post->year_started) ? (string)$post->year_started : "";
        $business->profile_img = !empty($post->profile_img) ? (string)$post->profile_img : "";
        $business->cover_img = !empty($post->cover_img) ? (string)$post->cover_img : "";
        $business->activation = 1;
        $business->reg_stage = 1;

        if($month_started && $year_started){
            $business->activation = 2;
            $business->reg_stage = 2;
        }

        if($profile_img && $cover_img){
            $business->activation = 3;
            $business->reg_stage = 3;
        }
        
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
                    'profile_img' => $profile_img,
                    'cover_img' => $cover_img,
                    'created' => $business->created,
                    'last_updated' => $business->last_updated
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = join(' ', $business->errors);
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>