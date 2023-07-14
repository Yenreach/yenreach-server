<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();

    $admin_string = !empty($_GET['admin_string']) ? (string)$_GET['admin_string'] : "";
    
    if(!empty($admin_string)){
        $admin = Admins::find_by_verify_string($admin_string);
        if(!empty($admin)){
            $feedbacks = Feedback::find_all();
            if(!empty($feedbacks)){
                $data_array = array();
                foreach($feedbacks as $feedback){
                    $data_array[] = array(
                        'id' => $feedback->id,
                        'name' => $feedback->name,
                        'email' => $feedback->email,
                        'feedback_string' => $feedback->feedback_string,
                        'subject' => $feedback->subject,
                        'message' => $feedback->message,
                        'status' => $feedback->status,
                        'created_at' => $feedback->created_at,
                        'updated_at' => $feedback->updated_at,
                    );
                }
                $return_array['status'] = 'success';
                $return_array['data'] = $data_array;
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Feedback was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Admin was found';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }

    

    $result = json_encode($return_array);
    echo $result;
?>