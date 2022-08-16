<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $email = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($email)){
        $user = Users::find_by_email($email);
        if(!empty($user)){
            $time = time();
            
            $string = "FakePass".$time."PassFake";
            $user->timer = $time;
            $user->password = $user->encrypt_value($user->timer, $string);
            if($user->insert()){
                $return_array['status'] = 'success';
                $return_array['data'] = array(
                        'id' => $user->id,
                        'verify_string' => $user->verify_string,
                        'name' => $user->name, 
                        'email' => $user->email,
                        'timer' => $user->timer,
                        'password' => $user->password,
                        'image' => $user->image,
                        'listed' => $user->listed,
                        'activation' => $user->activation,
                        'created' => $user->created,
                        'last_updated' => $user->last_updated,
                        'confirmed_email' => $user->confirmed_email
                    );
                    
                $subject = "Forgot Password";
                $content = "In order to retrieve your password, please click on <a href=\"https://yenreach.com/users/password_reset?";
                $content .= $user->verify_string."/".base64_encode($time)."/Yenreach".md5($time+1000)."\">this link</a>";
                
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
            $return_array['message'] = 'No User was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>