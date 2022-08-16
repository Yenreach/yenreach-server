<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $payment = new SubscriptionPayments();
        $payment->paymentplan_string = !empty($post->paymentplan_string) ? (string)$post->paymentplan_string : "";
        if(!empty($payment->paymentplan_string)){
            $plan = SubscriptionPaymentPlans::find_by_verify_string($payment->paymentplan_string);
            if(!empty($plan)){
                $subscription = BusinessSubscriptions::find_by_verify_string($plan->subscription_string);
                if(!empty($subscription)){
                    $payment->subscription_string = $subscription->verify_string;
                    $payment->user_type = !empty($post->user_type) ? (string)$post->user_type : "";
                    $payment->user_string = !empty($post->user_string) ? (string)$post->user_string : "";
                    $payment->business_string = !empty($post->business_string) ? (string)$post->business_string : "";
                    $payment->status = 1;
                    
                    if($payment->insert()){
                        $return_array['status'] = 'success';
                        $return_array['data'] = array(
                                'id' => $payment->id,
                                'verify_string' => $payment->verify_string,
                                'user_type' => $payment->user_type,
                                'user_string' => $payment->user_string,
                                'business_string' => $payment->business_string,
                                'subscription_string' => $payment->subscription_string, 
                                'paymentplan_string' => $payment->paymentplan_string,
                                'status' => $payment->status,
                                'created' => $payment->created,
                                'last_updated' => $payment->last_updated
                            );
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = join(' ', $payment->errors);
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Subscription Package was fetched';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Payment Plan was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Payment Plan was provided';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>