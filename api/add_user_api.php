<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $user = new Users();
        $user->name = !empty($post->name) ? (string)$post->name : "";
        $user->email = !empty($post->email) ? (string)$post->email : "";
        $password1 = !empty($post->password1) ? (string)$post->password1 : "";
        if(!empty($password1)){
            $password2 = !empty($post->password2) ? (string)$post->password2 : "";
            if($password1 == $password2){
                $user->timer = time();
                $user->password = $user->encrypt_value($user->timer, $password1);
                $user->refer_method = !empty($post->refer_method) ? (string)$post->refer_method : "";
                $user->activation = 2;
                $user->autho_level = 10;
                if($user->insert()){
                    $return_array['status'] = 'success';
                    $return_array['data'] = array(
                        'id' => $user->id,
                        'verify_string' => $user->verify_string,
                        'name' => $user->name,
                        'email' => $user->email,
                        'image' => $user->image, 
                        'listed' => $user->listed,
                        'refer_method' => $user->refer_method,
                        'activation' => $user->activation,
                        'autho_level' => $user->autho_level,
                        'created' => $user->created,
                        'last_updated' => $user->last_updated,
                        'confirmed_email' => $user->confirmed_email
                    );
                        
                    $subject = "Account Activation";
                    $content = '<i>'.$user->name.'</i>,';
                    $content .= '<p>';
                    $content .=     'Your Account creation on <a href="https://www.yenreach.com">Yenreach</a> was succcessful.';
                    $content .=     'In order to activate your Yenreach Account to have access to all that Yenreach has to offer, please click';
                    $content .=     '<br /><br/>';
                    $content .=     '<center><a href="https://yenreach.com/users/activate?'.base64_encode($user->verify_string).'">';
                    $content .=     '<button style="padding: 8px 15px; border-radius: 5px; background-color: green; color: #FFF; font-size: 17px; cursor: pointer;">This Link</button></a></center>';
                    $content .=     '<br></br>';
                    $content .= '</p>';
                    
                    $purl = "send_mail_api.php";
                    $pdata = [
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
                    perform_post_curl($purl, $pdata);
                    
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $user->errors);
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'Password should be correctly confirmed';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'Password must be provided';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
    // print_r($return_array)
?>