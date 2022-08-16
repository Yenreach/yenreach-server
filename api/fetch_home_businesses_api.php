<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $category_array = array();
    $subscribeds = Subscribers::find_active_subscribers();
    if(!empty($subscribeds)){
        foreach($subscribeds as $subscribed){
            $bus_categs = BusinessCategories::find_by_business_string($subscribed->business_string);
            if(!empty($bus_categs)){
                foreach($bus_categs as $bus_categ){
                    if(!in_array($bus_categ->category_string, $category_array)){
                        $category_array[] = $bus_categ->category_string;    
                    }
                    
                }
            }
        }
    }
    
    
    if(count($category_array) < 20){
        $approved_businesses = Businesses::find_approved_businesses();
        if(!empty($approved_businesses)){
            $categs_array = array();
            foreach($approved_businesses as $approved){
                $app_categs = BusinessCategories::find_by_business_string($approved->verify_string);
                if(!empty($app_categs)){
                    foreach($app_categs as $app_categ){
                        if(!in_array($app_categ->category_string, $categs_array)){
                            $categs_array[] = $app_categ->category_string;
                        }
                    }
                }
            }
            $counting = count($categs_array);
            $countly = count($category_array);
            if($counting > $countly){
                if($counting > 20){
                    $limit = 20;
                } else {
                    $limit = $counting;
                }
                foreach($categs_array as $array_string){
                    $thecount = count($category_array);
                    if($thecount < $limit){
                        if(!in_array($array_string, $category_array)){
                            $category_array[] = $array_string;
                        }
                    }
                }
            }
        }
    }
    
    
    if(!empty($category_array)){
        $counted_cat = count($category_array);
        if($counted_cat >= 4){
            $random_keys = array_rand($category_array, 4);
        } else {
            $random_keys = array_rand($category_array, $counted_cat);
        }
        
        $data_array = array();
        
        foreach($random_keys as $key){
            $businesses = array();
            $business_array = array();
            $category_string = $category_array[$key];
            $categ_busses = BusinessCategories::find_by_category_string($category_string);
            foreach($categ_busses as $busses){
                $business_array[] = $busses->business_string;
            }
            
            
            $packages = BusinessSubscriptions::find_all();
            
            foreach($packages as $package){
                foreach($business_array as $bus_string){
                    $check = Subscribers::check_active_business_package_subscription($bus_string, $package->verify_string);
                    if(!empty($check)){
                        if(!in_array($bus_string, $businesses)){
                            $businesses[] = $bus_string;
                        }
                    }
                }
            }
            
            $bus_count = count($businesses);
            $count_bus = count($business_array);
            if(($bus_count < 6) && ($count_bus > $bus_count)){
                foreach($business_array as $string_bus){
                    $counting = count($businesses);
                    if($counting < 6){
                        if(!in_array($string_bus, $businesses)){
                            $businesses[] = $string_bus;
                        }
                    }
                }
            }
            
            if(count($businesses) <= 6){
                $businesses_array = $businesses;
            } else {
                $businesses_array = array_slice($businesses, 0, 6);
            }
            
            foreach($businesses_array as $string){
                $job = Businesses::find_by_verify_string($string);
                if(!empty($job)){
                    $user = Users::find_by_verify_string($job->user_string);
                    $photos = BusinessPhotos::find_by_business_string($job->verify_string);
                    $categories = BusinessCategories::find_by_business_string($job->verify_string);
                    $array_businesses[] = array(
                            'id' => $job->id,
                            'verify_string' => $job->verify_string,
                            'name' => $job->name,
                            'description' => $job->description,
                            'user_string' => $job->user_string,
                            'user_name' => $user->name,
                            'user_email' => $user->email,
                            'subscription_string' => $job->subscription_string,
                            'category' => $job->category, 
                            'address' => $job->address,
                            'town' => $job->town,
                            'lga' => $job->lga,
                            'state' => $job->state,
                            'state_id' => $job->state_id,
                            'phonenumber' => $job->phonenumber,
                            'whatsapp' => $job->whatsapp,
                            'email' => $job->email,
                            'website' => $job->website,
                            'facebook_link' => $job->facebook_link,
                            'twitter_link' => $job->twitter_link,
                            'instagram_link' => $job->instagram_link,
                            'youtube_link' => $job->youtube_link,
                            'linkedin_link' => $job->linkedin_link,
                            'working_hours' => $job->working_hours,
                            'cv' => $job->cv,
                            'modifiedby' => $job->modifiedby,
                            'experience' => $job->experience,
                            'month_started' => $job->month_started,
                            'year_started' => $job->year_started,
                            'reg_stage' => $job->reg_stage,
                            'activation' => $job->activation,
                            'filename' => $job->filename,
                            'remarks' => $job->remarks,
                            'created' => $job->created,
                            'last_updated' => $job->last_updated,
                            'photos' => $photos,
                            'categories' => $categories
                        );
                }
            }
            $category = Categories::find_by_verify_string($category_string);
            $categ_with_business = array(
                    'category_id' => $category->id,
                    'category_string' => $category->verify_string,
                    'category' => $category->category,
                    'businesses' => $array_businesses
                );
            $data_array[] = $categ_with_business;
        }
        
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Business Category was fetched';
    }
    
    
    $result = json_encode($return_array);
    echo $result;
?>