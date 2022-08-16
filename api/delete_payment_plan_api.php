<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $plan = SubscriptionPaymentPlans::find_by_verify_string($string);
        if(!empty($plan)){
            if($plan->delete()){
                $sub = BusinessSubscriptions::find_by_verify_string($plan->subscription_string);
                if(!empty($sub)){
                    $subscription = $sub->package;
                } else {
                    $subscription = "";
                }
                $return_array['status'] = 'success';
                $return_array['data'] = array(
                        'id' => $plan->id,
                        'verify_string' => $plan->verify_string,
                        'subscription_string' => $plan->subscription_string, 
                        'subscription' => $subscription,
                        'plan' => $plan->plan,
                        'duration_type' => $plan->duration_type,
                        'duration' => $plan->duration,
                        'description' => $plan->description,
                        'price' => $plan->price,
                        'created' => $plan->created,
                        'last_updated' => $plan->last_updated
                    );
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Payment Plan Delete Failed';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Payment Plan was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>