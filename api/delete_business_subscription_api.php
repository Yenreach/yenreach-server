<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $sub = BusinessSubscriptions::find_by_verify_string($string);
        if(!empty($sub)){
            if($sub->delete()){
                $plans = SubscriptionPaymentPlans::find_by_subscription_string($sub->verify_string);
                if(!empty($plans)){
                    foreach($plans as $plan){
                        $plan->delete();
                    }
                }
                $return_array['status'] = 'success';
                $return_array['data'] = array(
                        'id' => $sub->id, 
                        'verify_string' => $sub->verify_string,
                        'package' => $sub->package,
                        'description' => $sub->description,
                        'photos' => $sub->photos,
                        'videos' => $sub->videos,
                        'slider' => $sub->slider,
                        'socialmedia' => $sub->socialmedia,
                        'branches' => $sub->branches,
                        'created' => $sub->created, 
                        'last_updated' => $sub->last_updated
                    );
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Subscription Package Delete Failed';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Business Subscription was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>