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
                        $photos = BusinessPhotos::find_by_business_string($business_string);
                        if(!empty($photos)){
                            $count = count($photos);
                        } else {
                            $count = 0;
                        }
                        if($count < $subscription->photos){
                            $photo = new BusinessPhotos();
                            $photo->user_string = !empty($post->user_string) ? (string)$post->user_string : "";
                            $photo->business_string = !empty($post->business_string) ? (string)$post->business_string : "";
                            $photo->size = !empty($post->size) ? (int)$post->size : 0;
                            if($photo->insert()){
                                $return_array['status'] = "success";
                                $return_array['data'] = $photo;
                            } else {
                                $return_array['status'] = "failed";
                                $return_array['message'] = join(' ', $photo->errors);
                            }
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = 'You can not upload more than '.$subscription->photos.' Photos. If you want to upload more, please check out <a href="subscription_packages">Subscription Packages</a>';
                        }
                    } else {
                        $photos = BusinessPhotos::find_by_business_string($business_string);
                        if(!empty($photos)){
                            $count = count($photos);
                        } else {
                            $count = 0;
                        }
                        if($count < 2){
                            $photo = BusinessPhotos();
                            $photo->user_string = !empty($post->user_string) ? (string)$post->user_string : "";
                            $photo->business_string = !empty($post->business_string) ? (string)$post->business_string : "";
                            $photo->size = !empty($post->size) ? (int)$post->size : 0;
                            if($photo->insert()){
                                $return_array['status'] = "success";
                                $return_array['data'] = $photo;
                            } else {
                                $return_array['status'] = "failed";
                                $return_array['message'] = join(' ', $photo->errors);
                            }
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = 'You can not upload more than 2 Photos. If you want to upload more, please check out <a href="subscription_packages">Subscription Packages</a>';
                        }
                    }
                } else {
                    $photos = BusinessPhotos::find_by_business_string($business_string);
                    if(!empty($photos)){
                        $count = count($photos);
                    } else {
                        $count = 0;
                    }
                    if($count < 2){
                        $photo = new BusinessPhotos();
                        $photo->user_string = !empty($post->user_string) ? (string)$post->user_string : "";
                        $photo->business_string = !empty($post->business_string) ? (string)$post->business_string : "";
                        $photo->size = !empty($post->size) ? (int)$post->size : 0;
                        if($photo->insert()){
                            $return_array['status'] = "success";
                            $return_array['data'] = $photo;
                        } else {
                            $return_array['status'] = "failed";
                            $return_array['message'] = join(' ', $photo->errors);
                        }
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = 'You can not upload more than 2 Photos. If you want to upload more, please check out <a href="subscription_packages">Subscription Packages</a>';
                    }
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
        $return_array['status'] = "failed";
        $return_array['message'] = "No data was provided";
    }
    
    $result = json_encode($return_array);
    echo $result;
?>