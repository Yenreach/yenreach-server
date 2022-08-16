<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $businesses = Businesses::find_by_user_string($string);
        if(!empty($businesses)){
            $data_array = array();
            foreach($businesses as $business){
                $data_array[] = array(
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
            }
            $return_array['status'] = 'success';
            $return_array['data'] = $data_array;
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Business registered';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>