<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $data = json_decode($post_json);
             
        $message = !empty($data->message) ? (string)$data->message : "";
        $feedback_string = !empty($data->feedback_string) ? (string)$data->feedback_string : "";
        $admin_string = !empty($data->admin_string) ? (string)$data->admin_string : "";

        if(!empty($admin_string)){
            $admin = Admins::find_by_verify_string($admin_string);
            if(!empty($admin)){
                $feedback = Feedback::find_by_feedback_string($data->feedback_string);
                if($feedback){
                    $return_array['status'] = 'success';
                    $return_array['message'] = "mail sent successfully";
        
                    $subject = "RE[Feedback] - " . $feedback->subject;
        
                    $purl = "send_mail_api.php";
                    $pdata = [
                        'ticket_id' => '',
                        'movement' => 'outgoing',
                        'from_name' => 'Yenreach',
                        'from_mail' => 'info@yenreach.com',
                        'recipient_name' => $feedback->name,
                        'recipient_mail' => $feedback->email,
                        'subject' => $subject,
                        'content' => $message,
                        'reply_name' => 'Yenreach',
                        'reply_mail' => 'info@yenreach.com'
                    ];
                    $mail_result = perform_post_curl($purl, $pdata);

                    $feedback->status = 1;
                    $feedback->update();
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = "feedback not found";
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Admin was found';
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