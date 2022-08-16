<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $application = BillboardApplications::find_by_verify_string($verify_string);
            if(!empty($application)){
                $user_string = !empty($post->user_string) ? (string)$post->user_string : "";
                if(!empty($user_string)){
                    if($application->user_string === $user_string){
                        if($application->stage == 3){
                            $time = time();
                            $today = strftime('%Y-%m-%d', $time);
                            if($today <= $application->proposed_start_date){
                                $return_array['status'] = 'success';
                                $return_array['message'] = 'Payment for the Application can now be made';
                            } else {
                                $return_array['status'] = 'failed';
                                $return_array['message'] = 'Time for the Application\'s payment has elapsed.';
                            }
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = 'This Application is not eligible for Payment';
                        }
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = 'You do not have the authorisation for this action';
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No User was provided';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Billboard Application was fetched';
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