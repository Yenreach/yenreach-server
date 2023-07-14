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
                $application->title = !empty($post->title) ? (string)$post->title : "";
                $application->text = !empty($post->text) ? (string)$post->text : "";
                $application->action_type = !empty($post->action_type) ? (string)$post->action_type : "";
                $application->action_link = !empty($post->action_link) ? (string)$post->action_link : "";
                $application->proposed_start_date = !empty($post->proposed_start) ? (string)$post->proposed_start : "";
                $application->status = 2;
                
                $total_active = BillboardApplications::find_period_total($application->proposed_start_date);
                
                if(count($total_active) < 5){
                    $time = time();
                    $today = strftime("%Y-%m-%d", $time);
                    if($application->proposed_start_date > $today){
                        $upload = !empty($_POST['upload']) ? (string)$_POST['upload'] : "";
                        if($upload == "yes"){
                            $old_filename = $application->filename;
                            $application->filename = "";
                        } else {
                            $old_filename = "";
                        }
                        if($application->insert()){
                            $user = Users::find_by_verify_string($application->user_string);
                            $application->old_filename = $old_filename;
                            $application->email = $user->email;
                            $application->name = $user->name;
                            
                            $return_array['status'] = 'success';
                            $return_array['data'] = $application;
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = join('<br />', $application->errors);
                        }
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = 'Your proposed Start date has to be a date in the future';
                    }
                } else {
                    $end_dates = [];
                    foreach($total_active as $active){
                        $end_dates[] = $active->end_date;
                    }
                    $earliest_available = min($end_dates);
                    
                    $message = "There are no available spaces on the Billboard for '{$application->proposed_start_date}'. The earliest available date for now is '{$earliest_available}'. We are so sorry for any inconvenience";
                    $return_array['status'] = 'failed';
                    $return_array['message'] = $message;
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