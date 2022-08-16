<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $business = Businesses::find_by_verify_string($string);
        if(!empty($business)){
            $other_facilities = array();
            $business_subscribe = Subscribers::find_business_latest_subscription($business->verify_string);
            if(!empty($business_subscribe)){
                $time = time();
                if(($business_subscribe->status == 1) && ($business_subscribe->true_expiry >= $time)){
                    $sub_string = $business_subscribe->subscription_string;
                } else {
                    $sub_string = "free";
                }
            } else {
                $sub_string = "free";
            }
            
            if($sub_string == "free"){
                $paid_facilities = Facilities::find_paid_facilities();
                if(!empty($paid_facilities)){
                    foreach($paid_facilities as $paid_facility){
                        $other_facilities[] = $paid_facility->facility;
                    }
                }
            } else {
                $other_facilities = array();
                $bus_subscription = BusinessSubscriptions::find_by_verify_string($sub_string);
                if(!empty($bus_subscription)){
                    $higher_subscriptions = BusinessSubscriptions::find_higher_subscriptions($bus_subscription->position);
                    if(!empty($higher_subscriptions)){
                        foreach($higher_subscriptions as $higher_subscription){
                            $this_facilities = Facilities::find_by_minimum_subscription($higher_subscription->verify_string);
                            if(!empty($this_facilities)){
                                foreach($this_facilities as $this_facility){
                                    $other_facilities[] = $this_facility->facility;
                                }
                            }
                        }
                    }
                }
            }
            
            if(!empty($other_facilities)){
                sort($other_facilities);
                $data_array = array();
                foreach($other_facilities as $other_facility){
                    $facility = Facilities::find_by_facility($other_facility);
                    $data_array[] = $facility;
                }
                $return_array['status'] = 'success';
                $return_array['data'] = $data_array;
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'There are no other facilities';
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