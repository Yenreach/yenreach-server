<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $sender = !empty($post->sender) ? (string)$post->sender : "";
        $body = !empty($post->content) ? (string)$post->content : "";
        $subject = !empty($post->subject) ? (string)$post->subject : "";
        
        if(!empty($sender)){
            if(!empty($body)){
                $users = Users::find_all();
                if(!empty($users)){
                    $user_emails = array();
                    
                    foreach($users as $user){
                        $businesses = Businesses::find_by_user_string($user->verify_string);
                        if(empty($businesses)){
                            $user_emails[] = array(
                                    'email' => $user->email,
                                    'name' => $user->name
                                );
                        }
                    }
                    if(!empty($user_emails)){
                        foreach($user_emails as $email){
                            
                            $content = '<i>'.$email['name'].'</i>';
                            $content .= '<p>'.nl2br($body).'</p>';
                                
                            $purl = "send_mail_api.php";
                            $pdata = [
                                    'ticket_id' => '',
                                    'movement' => 'outgoing',
                                    'from_name' => 'Yenreach',
                                    'from_mail' => $sender,
                                    'recipient_name' => $email['name'],
                                    'recipient_mail' => $email['email'],
                                    'subject' => $subject,
                                    'content' => $content,
                                    'reply_name' => 'Yenreach',
                                    'reply_mail' => $sender
                                ];
                                    
                            perform_post_curl($purl, $pdata);    
                               
                        }
                        $return_array['status'] = 'success';
                        $return_array['message'] = 'Bulk Mail sent successfully';
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = 'There are no Users without Businesses';
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No User was fetched';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Mail Content must be provided';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'Sender must be stated';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>