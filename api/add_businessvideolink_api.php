<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $business_string = !empty($post->business_string) ? (string)$post->business_string : "";
        if(!empty($business_string)){
            $business = Businesses::find_by_verify_string($business_string);
            if(!empty($business)){
                $sub = Subscribers::find_business_latest_subscription($business_string);
                if(!empty($sub)){
                    $time = time();
                    if(($sub->status == 1) && ($sub->true_expiry >= $time)){
                        $subscription = BusinessSubscriptions::find_by_verify_string($sub->subscription_string);
                        $former_links = BusinessVideoLinks::find_by_business_string($business_string);
                        if(!empty($former_links)){
                            $counted = count($former_links);
                        } else {
                            $counted = 0;
                        }
                        if($counted < $subscription->videos){
                            $link = new BusinessVideoLinks();
                            $link->platform = !empty($post->platform) ? (string)$post->platform : "";
                            $video_link = !empty($post->video_link) ? (string)$post->video_link : "";
                            if($link->platform == "YouTube"){
                                $extract = substr($video_link, 17);
                                $link->video_link = "https://www.youtube.com/embed/".$extract;
                            }
                            $link->real_link = $video_link;
                            $link->user_string = !empty($post->user_string) ? (string)$post->user_string : "";
                            $link->business_string = $business_string;
                            
                            if($link->insert()){
                                $return_array['status'] = 'success';
                                $return_array['data'] = array(
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
                            } else {
                                $return_array['status'] = 'failed';
                                $return_array['message'] = join(' ', $link->errors);
                            }
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = 'You can no longer add more Videos';
                        }
                    } else {
                        $return_array['status'] = 'success';
                        $return_array['message'] = 'You do not have an Active Subscription for this Business';
                    }
                } else {
                    $return_array['status'] = 'success';
                    $return_array['message'] = 'You need to Subscribe this Business to a Subscription Packae to add a Video';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Business was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Business Identification';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>