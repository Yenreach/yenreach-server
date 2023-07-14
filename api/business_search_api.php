<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $search_string = !empty($_GET['search_string']) ? (string)$_GET['search_string'] : "";
    $location = !empty($_GET['location']) ? (string)trim($_GET['location']) : "";
    
    if(!empty($search_string) || !empty($location)){
        if(empty($search_string)){
            $business_array;
            $packages = BusinessSubscriptions::find_all();
            foreach($packages as $package){
                $subscribers = Subscribers::find_active_by_subscription_string($package->verify_string);
                if(!empty($subscribers)){
                    foreach($subscribers as $subscriber){
                        $business = Businesses::find_by_verify_string($subscriber->business_string);
                        if(!empty($business)){
                            if($business->state == $location){
                                if(!in_array($business->verify_string, $business_array)){
                                    $business_array[] = $business->verify_string;
                                }
                            } else {
                                if(!empty($package->branches)){
                                    $branches = Branches::find_by_business_limit($business->verify_string, $package->branches);
                                    if(!empty($branches)){
                                        foreach($branches as $branch){
                                            if($branch->state == $location){
                                                if(!in_array($business->verify_string, $business_array)){
                                                    $business_array[] = $business->verify_string;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $businesses = Businesses::find_approved_businesses_by_state($location);
            if(!empty($businesses)){
                foreach($businesses as $business){
                    if(!in_array($business->verify_string, $business_array)){
                        $business_array[] = $business->verify_string;
                    }
                }
            }
            if(!empty($business_array)){
                $data_array = array();
                foreach($business_array as $string){
                    $business = Businesses::find_by_verify_string($string);
                    if(!empty($business)){
                        $photos = BusinessPhotos::find_by_business_string($business->verify_string);
                        $categories = BusinessCategories::find_by_business_string($business->verify_string);
                        $data_array[] = array(
                                'id' => $business->id,
                                'verify_string' => $business->verify_string,
                                'name' => $business->name,
                                'description' => $business->description,
                                'user_string' => $business->user_string,
                                'address' => $business->address,
                                'town' => $business->town,
                                'lga' => $business->lga,
                                'state' => $business->state,
                                'state_id' => $business->state_id,
                                'modifiedby' => $business->modifiedby,
                                'experience' => $business->experience,
                                'month_started' => $business->month_started,
                                'year_started' => $business->year_started,
                                'reg_stage' => $business->reg_stage,
                                'activation' => $business->activation,
                                'filename' => $business->filename,
                                'created' => $business->created,
                                'last_updated' => $business->last_updated,
                                'photos' => $photos,
                                'cover_img' => $business->cover_img,
                                'profile_img' => $business->profile_img,
                                'categories' => $categories
                            );
                    }
                }
                $return_array['status'] = 'success';
                $return_array['data'] = $data_array;
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Business was found for the Location';
            }
        } else {
            $search_array = explode(" ", $search_string);
            $businesses = Businesses::find_approved_businesses();
            if(!empty($businesses)){
                $business_array = array();
                foreach($businesses as $business){
                    if(!empty($location)){
                        if($business->state == $location){
                            $categories = BusinessCategories::find_by_business_string($business->verify_string);
                            if(!empty($categories)){
                                $category_array = array();
                                foreach($categories as $category){
                                    $category_array[] = $category->category;
                                }
                                $cats = join(' ', $category_array);
                            } else {
                                $cats = "";
                            }
                            
                            $all_details = $business->name." ".$cats." ".$business->address." ".$business->town." ".$business->lga." ".$business->state." ".$business->description;
                            $all_details .= " ".$business->email." ".$business->phonenumber;
                            $facility_array = array();
                            $fac_array = explode(',', $business->facilities);
                            foreach($fac_array as $fac_string){
                                $facility = Facilities::find_by_verify_string($fac_string);
                                if(!empty($facility)){
                                    $facility_array[] = $facility->facility;
                                }
                            }
                            if(!empty($facility_array)){
                                $facilities = join(", ", $facility_array);
                                $all_details .= $facilities;
                            }
                            
                            $counted = 0;
                            foreach($search_array as $search){
                                if(($search != "the") && ($search != "a") && ($search != "on") && ($search != "with") &&
                                ($search != "of") && ($search != "or") && ($search != "is") && ($search != "by") &&
                                ($search != "are") && ($search != "was") && ($search != "were") && ($search != "am") &&
                                ($search != "in") && ($search != "for") && ($search != "an")){
                                    if(strpos($all_details, $search) !== false){
                                        $counted += 1;
                                    }
                                }
                            }
                            if(!empty($counted)){
                                $business_array[$business->id] = $counted;
                            }
                        }
                    } else {
                        $categories = BusinessCategories::find_by_business_string($business->verify_string);
                        if(!empty($categories)){
                            $category_array = array();
                            foreach($categories as $category){
                                $category_array[] = $category->category;
                            }
                            $cats = join(' ', $category_array);
                        } else {
                            $cats = "";
                        }
                        
                        $all_details = $business->name." ".$cats." ".$business->address." ".$business->town." ".$business->lga." ".$business->state." ".$business->description;
                        $all_details .= " ".$business->email." ".$business->phonenumber;
                        $facility_array = array();
                        $fac_array = explode(',', $business->facilities);
                        foreach($fac_array as $fac_string){
                            $facility = Facilities::find_by_verify_string($fac_string);
                            if(!empty($facility)){
                                $facility_array[] = $facility->facility;
                            }
                        }
                        if(!empty($facility_array)){
                            $facilities = join(", ", $facility_array);
                            $all_details .= $facilities;
                        }
                        
                        $counted = 0;
                        foreach($search_array as $search){
                            if(($search != "the") && ($search != "a") && ($search != "on") && ($search != "with") &&
                            ($search != "of") && ($search != "or") && ($search != "is") && ($search != "by") &&
                            ($search != "are") && ($search != "was") && ($search != "were") && ($search != "am") &&
                            ($search != "in") && ($search != "for") && ($search != "an")){
                                if(strpos(strtolower($all_details), strtolower($search)) !== false){
                                    $counted += 1;
                                }
                            }
                        }
                        if(!empty($counted)){
                            $business_array[$business->id] = $counted;
                        }
                    }
                }
                if(!empty($business_array)){
                    arsort($business_array);
                    $data_array = array();
                    foreach($business_array as $key=>$value){
                        $business = Businesses::find_by_id($key);
                        $photos = BusinessPhotos::find_by_business_string($business->verify_string);
                        $categories = BusinessCategories::find_by_business_string($business->verify_string);
                        $data_array[] = array(
                                'id' => $business->id,
                                'verify_string' => $business->verify_string,
                                'name' => $business->name,
                                'description' => $business->description,
                                'user_string' => $business->user_string,
                                'address' => $business->address,
                                'town' => $business->town,
                                'lga' => $business->lga,
                                'state' => $business->state,
                                'state_id' => $business->state_id,
                                'modifiedby' => $business->modifiedby,
                                'experience' => $business->experience,
                                'month_started' => $business->month_started,
                                'year_started' => $business->year_started,
                                'reg_stage' => $business->reg_stage,
                                'activation' => $business->activation,
                                'filename' => $business->filename,
                                'created' => $business->created,
                                'last_updated' => $business->last_updated,
                                'photos' => $photos,
                                'cover_img' => $business->cover_img,
                                'profile_img' => $business->profile_img,
                                'categories' => $categories
                            );
                    }
                    $return_array['status'] = 'success';
                    $return_array['data'] = $data_array;
                } else {
                    $return_array['status'] = 'failed';
                    $message = 'No result was found for "'.$search_string.'"';
                    if(!empty($location)){
                        $message .= " in ".$location." State";
                    }
                    $return_array['message'] = $message;
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Business was fetched';
            }
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Search Parametre was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>