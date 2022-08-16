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
                        $branches = Branches::find_by_business_string($business_string);
                        $counted = count($branches);
                        if($counted < $subscription->branches){
                            $branch = new Branches();
                            $branch->business_string = !empty($post->business_string) ? (string)$post->business_string : "";
                            $branch->head_designation = !empty($post->head_designation) ? (string)$post->head_designation : "";
                            $branch->head_name = !empty($post->head_name) ? (string)$post->head_name : "";
                            $branch->phone = !empty($post->phone) ? (string)$post->phone : "";
                            $branch->email = !empty($post->email) ? (string)$post->email : "";
                            $branch->address = !empty($post->address) ? (string)$post->address : "";
                            $branch->town = !empty($post->town) ? (string)$post->town : "";
                            $branch->lga = !empty($post->lga) ? (string)$post->lga : "";
                            $branch->state_id = !empty($post->state_id) ? (int)$post->state_id : "";
                            $state = States::find_by_id($branch->state_id);
                            $branch->state = $state->name;
                            
                            if($branch->insert()){
                                $return_array['status'] = 'success';
                                $return_array['data'] = array(
                                        'id' => $branch->id, 
                                        'verify_string' => $branch->verify_string,
                                        'business_string' => $branch->business_string,
                                        'head_designation' => $branch->head_designation,
                                        'head_name' => $branch->head_name,
                                        'phone' => $branch->phone,
                                        'email' => $branch->email,
                                        'address' => $branch->address,
                                        'town' => $branch->town,
                                        'lga' => $branch->lga,
                                        'state_id' => $branch->state_id,
                                        'state' => $branch->state,
                                        'created' => $branch->created,
                                        'last_updates' => $branch->last_updates
                                    );
                            } else {
                                $return_array['status'] = 'failed';
                                $return_array['message'] = join(' ', $branch->errors);
                            }
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = 'You can no longer add more Branches';
                        }
                    } else {
                        $return_array['status'] = 'success';
                        $return_array['message'] = 'You do not have an Active Subscription for this Business';
                    }
                } else {
                    $return_array['status'] = 'success';
                    $return_array['message'] = 'You need to Subscribe this Business to a Subscription Packae to add a Branch';
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