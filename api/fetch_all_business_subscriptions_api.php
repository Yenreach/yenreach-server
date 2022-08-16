<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $subscriptions = BusinessSubscriptions::find_all();
    if(!empty($subscriptions)){
        $data_array = array();
        foreach($subscriptions as $subscription){
            $data_array[] = array(
                    'id' => $subscription->id,
                    'verify_string' => $subscription->verify_string,
                    'package' => $subscription->package,
                    'description' => $subscription->description,
                    'position' => $subscription->position,
                    'photos' => $subscription->photos,
                    'videos' => $subscription->videos,
                    'slider' => $subscription->slider,
                    'socialmedia' => $subscription->socialmedia,
                    'branches' => $subscription->branches,
                    'created' => $subscription->created,
                    'last_updated' => $subscription->last_updated
                );
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Business Subscription Package was fetched';
    }
   
    $result = json_encode($return_array);
    echo $result;
?>