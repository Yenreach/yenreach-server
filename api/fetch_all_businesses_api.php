<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $businesses = Businesses::find_all();
    if(!empty($businesses)){
        $data_array = array();
        foreach($businesses as $business){
            $user = Users::find_by_verify_string($business->user_string);
            if(!empty($user)){
                $owner_name = $user->name;
                $owner_email = $user->email;
            } else {
                $owner_name = "";
                $owner_email = "";
            }
            $data_array[] = array(
                    'id' => $business->id,
                    'verify_string' => $business->verify_string,
                    'name' => $business->name,
                    'description' => $business->description,
                    'user_string' => $business->user_string,
                    'owner_name' => $owner_name,
                    'owner_email' => $owner_email,
                    'subscription_string' => $business->subscription_string,
                    'category' => $business->category,
                    'facilities' => $business->facilities,
                    'address' => $business->address,
                    'state' => $business->state,
                    'state_id' => $business->state_id,
                    'phonenumber' => $business->phonenumber,
                    'whatsapp' => $business->whatsapp,
                    'email' => $business->email,
                    'website' => $business->website,
                    'facebook_link' => $business->facebook_link,
                    'instagram_link' => $business->instagram_link,
                    'youtube_link' => $business->youtube_link,
                    'linkedin_link' => $business->linkedin_link,
                    'working_hours' => $business->working_hours,
                    'cv' => $business->cv,
                    'reg_stage' => $business->reg_stage,
                    'modifiedby' => $business->modifiedby,
                    'experience' => $business->experience,
                    'created' => $business->created,
                    'last_updated' => $business->last_updated
                );
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Businesswas fetched';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>