<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $data = json_decode($post_json);
        
        $feedback = new Feedback();
        $time = time();
        $feedback->email = !empty($data->email) ? (string)$data->email : "";
        $feedback->name = !empty($data->name) ? (string)$data->name : "";
        $feedback->subject = !empty($data->subject) ? (string)$data->subject : "";
        $feedback->message = !empty($data->message) ? (string)$data->message : "";
        $feedback->created_at = $time;
        $feedback->updated_at = $time;

        if($feedback->insert("create")){
            $return_array['status'] = 'success';
            $return_array['message'] = "feedback sent successfully";

            $subject = "RE[Feedback] - " . $feedback->subject;

            $content = '<p>';
            $content .=     'Thank you for contacting us on the subject matter - '.strtoupper($feedback->subject).'.<br />';
            $content .=     'We are looking into the matter and will get back to you as soon as possible.<br />';
            $content .= '</p>';
            $content .= '<br /><br/>';
            $content .= '<p>';
            $content .=     'Regards,<br />';
            $content .=     'Yenreach Support Team';   
            $content .= '</p>';


            $purl = "send_mail_api.php";
            $pdata = [
                'ticket_id' => '',
                'movement' => 'outgoing',
                'from_name' => 'Yenreach',
                'from_mail' => 'info@yenreach.com',
                'recipient_name' => $feedback->name,
                'recipient_mail' => $feedback->email,
                'subject' => $subject,
                'content' => $content,
                'reply_name' => 'Yenreach',
                'reply_mail' => 'info@yenreach.com'
            ];
            $result = perform_post_curl($purl, $pdata);
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = join(' ', $feedback->errors);
        }

    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>