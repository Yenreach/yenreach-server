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
                        $num = $subscription->photos;
                    } else {
                        $num = 2;
                    }
                } else {
                    $num = 2;
                }
            } else {
                $num = 2;
            }
            
            if(!empty($num)){
                $photos = BusinessPhotos::find_by_business_limit($business->verify_string, $num);
                if(!empty($photos)){
                    $data_array = array();
                    
                    foreach($photos as $photo){
                        $data_array[] = array(
                                'id' => $photo->id,
                                'verify_string' => $photo->verify_string,
                                'user_string' => $photo->user_string,
                                'business_string' => $photo->business_string,
                                'filename' => $photo->filename,
                                'filepath' => $photo->filepath,
                                'size' => $photo->size,
                                'created' => $photo->created,
                                'last_updated' => $photo->last_updated    
                            );
                    }
                    $return_array['status'] = 'success';
                    $return_array['data'] = $data_array;
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Business Photo was fetched';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Business cannot fetch any Photo';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Business was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>