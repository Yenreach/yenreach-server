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
                $application->stage = 1;
                $application->remarks = !empty($post->remarks) ? (string)$post->remarks : "";
                $application->agent_type = !empty($post->agent_type) ? (string)$post->agent_type : "";
                $application->agent_string = !empty($post->agent_string) ? (string)$post->agent_string : "";
                if($application->insert()){
                    $user = Users::find_by_verify_string($application->user_string);
                    $subject = "Yenreach Billboard Application - {$application->code}";
                    $content = 'Hello <i>'.$user->name.'</i>,';
                    $content .= '<p>';
                    $content .=     'Your Application for an Advert Space on Yenreach Billboard has been reverted back to you with the follwing message:';
                    $content .= '</p>';
                    $content .= '<p><i><center>'.nl2br($application->remarks).'</center></i></p>';
                    $content .= '<p>';
                    $content .=     'Please make the neccessary corrections and make the required update. Go to <a href="https://yenreach.com/users/billboard_apply?'.$application->verify_string.'">';
                    $content .=     'https://yenreach.com/users/billboard_applied?'.$application->verify_string.'</a> to effect the corrections';
                    $content .= '</p>';
                    $content .= '<p>';
                    $content .=     'For further enquiries or complaints, you can send a mail to <a href="mailto:support@yenreach.com">support@yenreach.com</a>';
                    $content .= '</p>';
                    $content .= '<p>';
                    $content .=     '<i>Yenreach Support Team</i>';
                    $content .= '</p>';
                    
                    $spurl = "send_mail_api.php";
                    $spdata = [
                            'ticket_id' => '',
                            'movement' => 'outgoing',
                            'from_name' => 'Yenreach',
                            'from_mail' => 'info@yenreach.com',
                            'recipient_name' => $user->name,
                            'recipient_mail' => $user->email,
                            'subject' => $subject,
                            'content' => $content,
                            'reply_name' => 'Yenreach',
                            'reply_mail' => 'info@yenreach.com'
                        ];
                    
                    perform_post_curl($spurl, $spdata);
                    $return_array['status'] = 'success';
                    $return_array['data'] = $application;
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $application->errors);
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