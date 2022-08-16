<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $business_string = !empty($post->business_string) ? (string)$post->business_string : "";
        if(!empty($business_string)){
            $business = Businesses::find_by_verify_string($business_string);
            
            $bus_subscription = Subscribers::find_business_latest_subscription($business->verify_string);
            if(!empty($bus_subscription)){
                $time = time();
                if(($bus_subscription->status == 1) && ($bus_subscription->true_expiry >= $time)){
                    $subscription = BusinessSubscriptions::find_by_verify_string($bus_subscription->subscription_string);
                    if(!empty($subscription)){
                        $sub_position = $subscription->position;
                    } else {
                        $sub_position = 999999;
                    }
                } else {
                    $sub_position = 999999;
                }
            } else {
                $sub_position = 999999;
            }
            $facilities = !empty($post->facilities) ? (string)$post->facilities : "";
            if(!empty($facilities)){
                $facilities_array = array();
                $facil_array = explode(',', $facilities);
                foreach($facil_array as $facil){
                    $facility = Facilities::find_by_verify_string($facil);
                    if(!empty($facility)){
                        if($facility->minimum_subscription == "free"){
                            $facilities_array[] = $facility->verify_string;
                        } else {
                            $facil_subscription = BusinessSubscriptions::find_by_verify_string($facility->minimum_subscription);
                            if($facil_subscription->position >= $sub_position){
                                $facilities_array[] = $facility->verify_string;
                            }
                        }
                    }
                }
                $business->facilities = join(',', $facilities_array);
            } else {
                $business->facilities = "";
            }
            if($business->insert()){
                $return_array['status'] = 'success';
                $return_array['message'] = 'Business Facilities Updated successfully';
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = join(', ', $business->errors);
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Mode of Business Identification';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>