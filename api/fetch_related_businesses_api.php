<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $business = Businesses::find_by_verify_string($string);
        if(!empty($business)){
            $business_array = array();
            
            $buscategs = BusinessCategories::find_by_business_string($business->verify_string);
            if(!empty($buscategs)){
                $category_businesses = array();
                foreach($buscategs as $categ){
                    $busins = BusinessCategories::find_by_category_string($categ->category_string);
                    if(!empty($busins)){
                        foreach($busins as $busin){
                            if(($busin->business_string != $business->verify_string) && (!in_array($busin->business_string, $category_businesses))){
                                $bus = Businesses::find_by_verify_string($busin->business_string);
                                if($bus->reg_stage == 4){
                                    $category_businesses[] = $bus->verify_string;
                                }
                            }
                        }
                    }
                }
                if(!empty($category_businesses)){
                    $packages = BusinessSubscriptions::find_all();
                    if(!empty($packages)){
                        foreach($packages as $package){
                            foreach($category_businesses as $categ_business){
                                $check = Subscribers::check_active_business_package_subscription($categ_business, $package->verify_string);
                                if(!empty($check)){
                                    if(!in_array($categ_business, $business_array)){
                                        $business_array[] = $categ_business;
                                    }
                                }
                            }
                        }
                    }
                    foreach($category_businesses as $categ_business){
                        if(!in_array($categ_business, $business_array)){
                            $business_array[] = $categ_business;
                        }
                    }
                }
                
            } else {
                $packages = BusinessSubscriptions::find_all();
                if(!empty($packages)){
                    foreach($packages as $package){
                        $subscribers = Subscribers::find_active_by_subscription_string($package->verify_string);
                        if(!empty($subscribers)){
                            foreach($subscribers as $subscriber){
                                if((!in_array($subscriber->business_string, $business_array)) && ($subscriber->business_string != $business->verify_string)){
                                    $bus = Businesses::find_by_verify_string($subscriber->business_string);
                                    if($bus->reg_stage == 4){
                                        $business_array[] = $bus->verify_string;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            $count_picked = count($business_array);
            if($count_picked < 8){
                if(!empty($buscategs)){
                    $section_array = array();
                    foreach($buscategs as $scateg){
                        if(!empty($scateg->section_string)){
                            if(!in_array($scateg->section_string, $section_array)){
                                $section_array[] = $scateg->section_string;
                            }
                        }
                    }
                    if(!empty($section_array)){
                        foreach($section_array as $section){
                            $sec_businesses = BusinessCategories::find_by_section_string($section);
                            if(!empty($sec_businesses)){
                                foreach($sec_businesses as $sec_business){
                                    if($sec_business->business_string != $business->verify_string){
                                        $secbus = Businesses::find_by_verify_string($sec_business->business_string);
                                        if(!empty($secbus)){
                                            if(($secbus->reg_stage == 4) && (!in_array($secbus->verify_string, $business_array))){
                                                $business_array[] = $secbus->verify_string;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            $counted = count($business_array);
            $businesses = Businesses::find_approved_businesses();
            $count_bus = count($businesses);
            if(($count_bus >= 8) && ($counted < 8)){
                
                    foreach($businesses as $workplace){
                        $counting = count($business_array);
                        if($counting < 20){
                            if((!in_array($workplace->verify_string, $business_array)) && ($business->verify_string != $workplace->verify_string)){
                                $business_array[] = $workplace->verify_string;
                            }   
                        }
                    }
                
            }
            
            
            if(!empty($business_array)){
                $data_array = array();
                $counted = count($business_array);
                if($counted >= 6){
                    $randomkeys = array_rand($business_array, 6);    
                } else {
                    $randomkeys = array_rand($business_array, $counted);
                }
                
                foreach($randomkeys as $key){
                    $string = $business_array[$key];
                    $job = Businesses::find_by_verify_string($string);
                    if(!empty($job)){
                        $user = Users::find_by_verify_string($job->user_string);
                        $photos = BusinessPhotos::find_by_business_string($job->verify_string);
                        $categories = BusinessCategories::find_by_business_string($job->verify_string);
                        $data_array[] = array(
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
                $return_array['status'] = 'success';
                $return_array['data'] = $data_array;   
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Related Business was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Business was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>