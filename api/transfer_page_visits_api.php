<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $user_string = !empty($post->user_string) ? (string)$post->user_string : "";
        if(!empty($user_string)){
            $user = Users::find_by_verify_string($user_string);
            if(!empty($user)){
                $cookie = !empty($post->cookie) ? (string)$post->cookie : "";
                if(!empty($cookie)){
                    $visits = PageVisits::find_by_user_string($cookie);
                    if(!empty($visits)){
                        foreach($visits as $visit){
                            $visited = PageVisits::find_previous_visit($visit->business_string, $user_string, $visit->day, $visit->month, $visit->year);
                            if(empty($visited)){
                                $visit->user_string = $user_string;
                                $visit->save();
                            } else {
                                $visited->frequency += $visit->frequency;
                                if($visit->last_updated > $visited->last_updated){
                                    $visited->last_updated = $visit->last_updated;
                                }
                                $visited->save();
                            }
                        }
                        $return_array['status'] = 'success';
                        $return_array['message'] = 'Page Visit transferred successfully';
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = 'No Page Visit for this Cookie';
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Cookie was provided';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No User was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No means of User Identification';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>