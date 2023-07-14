<?php
    require_once("../../includes_yenreach/initialize.php");
    $user = new Users();
    $return_array = array();
    $emaillist = new EmailList();
    // $users = ["oebiyeladouemmanuel@gmail.com", "nicholasduadei14@gmail.com", "nicholasduadei7@gmail.com", "ebiyeladou@gmail.com"];

    $users = $user->find_all();

    foreach($users as $user){
        if($user->email_track < 7){
            // $subject = "Account Activation";
            // $content = 'count: ';
            // $content .= '<p>';
            // $content .=     'Your Account creation on <a href="https://www.yenreach.com">The Yenreach Platform</a> was succcessful.';
            // $content .=     'In order to activate your yenreach Account to have access to all that Yenreach has to offer, please click';
            // $content .=     '<br /><br/>';
            // $content .=     '<center><a href="https://yenreach.com/users/activate?">';
            // $content .=     '<button style="padding: 8px 15px; border-radius: 5px; background-color: green; color: #FFF; font-size: 17px; cursor: pointer;">This Link</button></a></center>';
            // $content .=     '<br></br>';
            // $content .= '</p>';
            
            $email = $emaillist->find_by_id($user->email_track);
            
            $purl = "send_mail_api.php";
            $pdata = [
                'ticket_id' => '',
                'movement' => 'outgoing',
                'from_name' => 'Yenreach',
                'from_mail' => 'info@yenreach.com',
                'recipient_name' => $user->name,
                'recipient_mail' => $user->email,
                'subject' => $email->title,
                'content' => $email->content,
                'reply_name' => 'Yenreach',
                'reply_mail' => 'info@yenreach.com'
            ];
            $result = perform_post_curl($purl, $pdata);

            $user->email_track = $user->email_track + 1;
            $user->update();

            }
        }
        $res = json_encode($users);
        print_r($users);
?>