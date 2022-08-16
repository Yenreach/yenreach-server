<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $subscribe = Subscribers::find_by_verify_string($string);
        if(!empty($subscribe)){
            $subs = BusinessSubscriptions::find_by_verify_string($subscribe->subscription_string);
            if(!empty($subs)){
                $subscription = $subs->package;
            } else {
                $subscription = "";
            }
            $plan = SubscriptionPaymentPlans::find_by_verify_string($subscribe->paymentplan_string);
            if(!empty($plan)){
                $payment_plan = $plan->plan;
            } else {
                $payment_plan = "";
            }
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                    'id' => $subscribe->id,
                    'verify_string' => $subscribe->verify_string,
                    'user_string' => $subscribe->user_string,
                    'business_string' => $subscribe->business_string,
                    'subscription_string' => $subscribe->subscription_string,
                    'subscription' => $subscription,
                    'paymentplan_string' => $subscribe->paymentplan_string,
                    'payment_plan' => $payment_plan,
                    'amount_paid' => $subscribe->amount_paid,
                    'duration_type' => $subscribe->duration_type,
                    'duration' => $subscribe->duration,
                    'started' => $subscribe->started,
                    'expired' => $subscribe->expired,
                    'true_expiry' => $subscribe->true_expiry,
                    'status' => $subscribe->status,
                    'payment_method' => $subscribe->payment_method,
                    'auto_renew' => $subscribe->auto_renew,
                    'agent_type' => $subscribe->agent_type,
                    'agent_string' => $subscribe->agent_string,
                    'created' => $subscribe->created,
                    'last_updated' => $subscribe->last_updated
                );
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