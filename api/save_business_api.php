<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $user_string = !empty($post->user_string) ? (string)$post->user_string : "";
        $business_string = !empty($post->business_string) ? (string)$post->business_string : "";
        
        if(!empty($user_string)){
            $user = Users::find_by_verify_string($user_string);
            if(!empty($user)){
                if(!empty($business_string)){
                    $business = Businesses::find_by_verify_string($business_string);
                    if(!empty($business)){
                        $saved = SavedBusinesses::find_by_user_business($user_string, $business_string);
                        if(empty($saved)){
                            $save = new SavedBusinesses();
                            $save->user_string = $user_string;
                            $save->business_string = $business_string;
                            if($save->save()){
                                $return_array['status'] = 'success';
                                $return_array['data'] = $save;
                            } else {
                                $return_array['status'] = 'failed';
                                $return_array['message'] = 'Business was not saved';
                            }
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = 'Business already Added by You';
                        }
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = 'No Business was fetched';
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Business was provided';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No User was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No User was provided';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>