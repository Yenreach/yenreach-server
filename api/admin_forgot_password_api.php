<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $username = !empty($_GET['username']) ? (string)$_GET['username'] : "";
    if(!empty($username)){
        $admin = Admins::find_by_username($username);
        if(!empty($admin)){
            $time = time();
            $admin->timer = $time;
            $pword = "FakePass".$time."PassFake";
            $admin->password = $admin->encrypt_value($admin->timer, $pword);
            if($admin->insert('update')){
                $subject = "Forgot Password";
                $content = "In order to retrieve your password, please click on <a href=\"https://admin.yenreach.com/activate?";
                $content .= $admin->verify_string."/".base64_encode($time)."/Yenreach".md5($time)."\">this link</a>";
                
                $purl = "send_mail_api.php";
                $pdata = [
                        'ticket_id' => '',
                        'movement' => 'outgoing',
                        'from_name' => 'Yenreach',
                        'from_mail' => 'info@yenreach.com',
                        'recipient_name' => $admin->name,
                        'recipient_mail' => $admin->personal_email,
                        'subject' => $subject,
                        'content' => $content,
                        'reply_name' => 'Yenreach',
                        'reply_mail' => 'info@yenreach.com'
                    ];
                perform_post_curl($purl, $pdata);
                
                $return_array['status'] = 'success';
                $return_array['data'] = array(
                        'id' => $admin->id,
                        'verify_string' => $admin->verify_string,
                        'name' => $admin->name,
                        'username' => $admin->username,
                        'personal_email' => $admin->personal_email,
                        'official_email' => $admin->official_email,
                        'phone' => $admin->phone,
                        'activation' => $admin->activation,
                        'autho_level' => $admin->autho_level,
                        'created' => $admin->created,
                        'last_updated' => $admin->last_updated
                    );
            } else {
                $return_array['status'] = "failed";
                $return_array['message'] = join(' ', $admin->$errors);
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Admin was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>