<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $payment = SubscriptionPayments::find_by_verify_string($string);
        if(!empty($payment)){
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
            $return_array['message'] = 'No Subscription Payment was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>