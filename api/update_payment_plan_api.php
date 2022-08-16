<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $plan = SubscriptionPaymentPlans::find_by_verify_string($verify_string);
            if(!empty($plan)){
                $plan->plan = !empty($post->plan) ? (string)$post->plan : "";
                $plan->duration_type = !empty($post->duration_type) ? (int)$post->duration_type : "";
                $plan->duration = !empty($post->duration) ? (int)$post->duration : "";
                $plan->price = !empty($post->price) ? (double)$post->price : "";
                $plan->description = !empty($post->description) ? (string)$post->description : "";
                
                if($plan->insert()){
                    $return_array['status'] = 'success';
                    $return_array['data'] = array(
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
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $plan->errors);
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Payment Plan was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No means of Identification';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>