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
                        $num = $subscription->videos;
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
                $links = BusinessVideoLinks::find_by_business_limit($business->verify_string, $num);
                if(!empty($links)){
                    $data_array = array();
                    foreach($links as $link){
                        $data_array[] = array(
                                'id' => $link->id,
                                'verify_string' => $link->verify_string,
                                'user_string' => $link->user_string,
                                'business_string' => $link->business_string,
                                'video_link' => $link->video_link,
                                'real_link' => $link->real_link,
                                'platform' => $link->platform,
                                'created' => $link->created,
                                'last_updated' => $link->last_updated
                            );
                    }
                    $return_array['status'] = 'success';
                    $return_array['data'] = $data_array;
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Video was fetched';
                }
            } else { 
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Business Video Links fetchin not working';
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