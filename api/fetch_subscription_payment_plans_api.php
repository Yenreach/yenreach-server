<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $plans = SubscriptionPaymentPlans::find_by_subscription_string($string);
        if(!empty($plans)){
            $data_array = array();
            foreach($plans as $plan){
                $data_array[] = array(
                        'id' => $plan->id,
                        'verify_string' => $plan->verify_string,
                        'subscription_string' => $plan->subscription_string, 
                        'plan' => $plan->plan,
                        'duration_type' => $plan->duration_type,
                        'duration' => $plan->duration,
                        'description' => $plan->description,
                        'price' => $plan->price,
                        'created' => $plan->created,
                        'last_updated' => $plan->last_updated
                    );
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Payment Plan was added';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>