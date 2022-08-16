<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $business = Businesses::find_by_verify_string($string);
        if(!empty($business)){
            $facilities_array = array();
            
            $free_subscriptions = Facilities::find_by_minimum_subscription('free');
            if(!empty($free_subscriptions)){
                foreach($free_subscriptions as $free_subscription){
                    $facilities_array[] = $free_subscription->facility;
                }
            }
            
            $business_subscribe = Subscribers::find_business_latest_subscription($business->verify_string);
            if(!empty($business_subscribe)){
                $time = time();
                if(($business_subscribe->status == 1) && ($business_subscribe->true_expiry >= $time)){
                    $subscription = BusinessSubscriptions::find_by_verify_string($business_subscribe->subscription_string);
                    $subscribe_facilities = Facilities::find_by_minimum_subscription($subscription->verify_string);
                    if(!empty($subscribe_facilities)){
                        foreach($subscribe_facilities as $subscribe_facility){
                            $facilities_array[] = $subscribe_facility->facility;
                        }
                    }
                    
                    $lower_subscriptions = BusinessSubscriptions::find_lower_subscriptions($subscription->position);
                    if(!empty($lower_subscriptions)){
                        foreach($lower_subscriptions as $l_sub){
                            $facilities = Facilities::find_by_minimum_subscription($l_sub->verify_string);
                            if(!empty($facilities)){
                                foreach($facilities as $facilit){
                                    $facilities_array[] = $facilit->facility;
                                }
                            }
                        }
                    }
                }
            }
            
            if(!empty($facilities_array)){
                sort($facilities_array);
                
                $data_array = array();
                foreach($facilities_array as $facil){
                    $facility = Facilities::find_by_facility($facil);
                    if(!empty($facility)){
                        $data_array[] = array(
                                'id' => $facility->id,
                                'verify_string' => $facility->verify_string,
                                'facility' => $facility->facility,
                                'minimum_subscription' => $facility->minimum_subscription,
                                'activation' => $facility->activation,
                                'created' => $facility->created,
                                'last_updated' => $facility->last_updated
                            );
                    }
                }
                $return_array['status'] = 'success';
                $return_array['data'] = $data_array;
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Facilities for this Business to pick from';
            }
        } else{
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