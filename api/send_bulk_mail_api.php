<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $sender = !empty($post->sender) ? (string)$post->sender : "";
        $body = !empty($post->content) ? (string)$post->content : "";
        $subject = !empty($post->subject) ? (string)$post->subject : "";
        $id = $post->id;

        $test = array(
                'sender' => $sender,
                'body' => $body,
                'subject' => $subject,
            );
        
        if(!empty($sender)){
            if(!empty($body)){
                $users = Users::find_all();
                if(!empty($users)){
                    foreach($users as $user){
                        $business_array = array();
                        $businesses = Businesses::find_by_user_string($user->verify_string);
                        if(!empty($businesses)){
                            foreach($businesses as $business){
                                if(!empty($business->email)){
                                    $business_array[] = $business->email;
                                }
                            }
                        }
                        
                        
                        $content = '<i>'.$user->name.'</i>';
                        $content .= '<p>'.nl2br($body).'</p>';
                        if($sender == "michael@yenreach.com"){
                            $person = "Michael from Yenreach";
                        } else {
                            $person = "Yenreach";
                        }
                        
                        $purl = "send_mail_api.php";
                        $pdata = [
                                'ticket_id' => '',
                                'movement' => 'outgoing',
                                'from_name' => $person,
                                'from_mail' => $sender,
                                'recipient_name' => $user->name,
                                'recipient_mail' => $user->email,
                                'recipient_cc_name' => '',
                                'recipient_cc' => join(',', $business_array),
                                'subject' => $subject,
                                'content' => $content,
                                'reply_name' => $person,
                                'reply_mail' => $sender
                            ];
                            
                        perform_post_curl($purl, $pdata);
                    }
                    
                    $return_array['status'] = 'success';
                    $return_array['message'] = 'Bulk Mail sent successfully';
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
    $result2 = json_encode($test);
    echo $result2;
?>