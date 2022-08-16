<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $business = Businesses::find_by_verify_string($string);
        if(!empty($business)){
            $sub = Subscribers::find_business_latest_subscription($business->verify_string);
            if(!empty($sub)){
                $time = time();
                if(($sub->status == 1) && ($sub->true_expiry >= $time)){
                    $subscription = BusinessSubscriptions::find_by_verify_string($sub->subscription_string);
                    if(!empty($subscription)){
                        $num = $subscription->branches;
                    } else {
                        $num = 0;
                    }
                } else {
                    $num = 0;
                }
            } else {
                $num = 0;
            }
            if(!empty($num)){
                $branches = Branches::find_by_business_limit($business->verify_string, $num);
                if(!empty($branches)){
                    $data_array = array();
                    foreach($branches as $branch){
                        $data_array[] = array(
                                'id' => $branch->id, 
                                'verify_string' => $branch->verify_string,
                                'business_string' => $branch->business_string,
                                'head_designation' => $branch->head_designation,
                                'head_name' => $branch->head_name,
                                'phone' => $branch->phone,
                                'email' => $branch->email,
                                'address' => $branch->address,
                                'town' => $branch->town,
                                'lga' => $branch->lga,
                                'state_id' => $branch->state_id,
                                'state' => $branch->state,
                                'created' => $branch->created,
                                'last_updates' => $branch->last_updates
                            );
                    }
                    $return_array['status'] = 'success';
                    $return_array['data'] = $data_array;
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Branch was fetched';
                }
            } else { 
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Business Branches fetching not working';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Business was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Business Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>