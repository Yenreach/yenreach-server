<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $sub = Subscribers::find_business_latest_subscription($string);
        if(!empty($sub)){
            $package = BusinessSubscriptions::find_by_verify_string($sub->subscription_string);
            $plans = SubscriptionPaymentPlans::find_by_verify_string($sub->paymentplan_string);
            if(!empty($package)){
                $subscription = $package;
            } else {
                $subscription = "";
            }
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                    'id' => $sub->id,
                    'verify_string' => $sub->verify_string,
                    'user_string' => $sub->user_string,
                    'business_string' => $sub->business_string,
                    'subscription_string' => $sub->subscription_string,
                    'subscription' => $subscription,
                    'paymentplan_string' => $sub->paymentplan_string,
                    'plans' => $plans,
                    'amount_paid' => $sub->amount_paid,
                    'duration_type' => $sub->duration_type,
                    'duration' => $sub->duration,
                    'started' => $sub->started,
                    'expired' => $sub->expired,
                    'true_expiry' => $sub->true_expiry,
                    'status' => $sub->status,
                    'payment_method' => $sub->payment_method,
                    'agent_type' => $sub->agent_type,
                    'agent_string' => $sub->agent_string, 
                    'created' => $sub->created,
                    'last_updated' => $sub->last_updated
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No current Subscription for this Business';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>