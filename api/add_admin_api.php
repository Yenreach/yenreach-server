<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $admin = new Admins();
        $time = time();
        $admin->name = !empty($post->name) ? (string)$post->name : "";
        $admin->username = !empty($post->username) ? (string)$post->username : "";
        $admin->personal_email = !empty($post->personal_email) ? (string)$post->personal_email : "";
        $admin->official_email = !empty($post->official_email) ? (string)$post->official_email : "";
        $admin->phone = !empty($post->phone) ? (string)$post->phone : "";
        $admin->autho_level = !empty($post->autho_level) ? (int)$post->autho_level : 0;
        $admin->timer = $time;
        $password_string = "FakePass".$time."PassFake";
        $admin->password = $admin->encrypt_value($admin->timer, $password_string);
        if($admin->insert("create")){
            $subject = "Addition as Admin";
            $content = "<p>You have just been added as an Admin on <a href=\"https://yenreach.com\">Yeanreach</a> with the Username <b>".$admin->username."</b>.</p>";
            $content .= "<p>";
            $content .=     "In order to activate your Account, please click <a href=\"https://admin.yenreach.com/activate?".$admin->verify_string;
            $content .=     "/".base64_encode($time)."/YENREACH".md5($time)."\">this link</a>";
            $content .= "</p>";
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
            $return_array['status'] = 'failed';
            $return_array['message'] = join(' ', $admin->errors);
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>