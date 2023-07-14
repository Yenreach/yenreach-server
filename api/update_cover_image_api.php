<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $business_string = !empty($post->business_string) ? (string)$post->business_string : "";
        $user_string = !empty($post->user_string) ? (string)$post->user_string : "";
        if(!empty($user_string)){
            $user = Users::find_by_verify_string($user_string);
            if(!empty($user)){
                if(!empty($business_string)){
                    $business = Businesses::find_by_verify_string($business_string);
                    if(!empty($business)){
                        $business->cover_img = !empty($post->cover_img) ? (string)$post->cover_img : "";
                        
                        if($business->insert()){
                            $return_array['status'] = 'success';
                            $return_array['message'] = 'photo url was updated successfully';
                            $return_array['data'] = array(
                                'cover_img' => $business->cover_img
                            );
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = join(' ', $business->errors);
                        }
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = 'No Business was fetched';
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No means of Identification';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No User was fetched';
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