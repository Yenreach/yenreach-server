<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $business_array = array();
    $packages = BusinessSubscriptions::find_all();
    if(!empty($packages)){
        foreach($packages as $package){
            $subscribers = Subscribers::find_active_by_subscription_string($package->verify_string);
            if(!empty($subscribers)){
                foreach($subscribers as $subscriber){
                    if(!in_array($subscriber->business_string, $business_array)){
                        $bus = Businesses::find_by_verify_string($subscriber->business_string);
                        if($bus->reg_stage == 4){
                            $business_array[] = $bus->verify_string;
                        }
                    }
                }
            }
        }
    }
    $businesses = Businesses::find_approved_businesses();
    if(!empty($businesses)){
        foreach($businesses as $busin){
            if(!in_array($busin->verify_string, $business_array)){
                $business_array[] = $busin->verify_string;
            }
        }
    }
    
    if(!empty($business_array)){
        $data_array = array();
        
        foreach($business_array as $verify_string){
            $business = Businesses::find_by_verify_string($verify_string);
            $user = Users::find_by_verify_string($business->user_string);
            $photos = BusinessPhotos::find_by_business_string($business->verify_string);
            $categories = BusinessCategories::find_by_business_string($business->verify_string);
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
                    'categories' => $categories
                );
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'There are no approved Businesses';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>