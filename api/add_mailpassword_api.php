<?php
    require_once("../../includes_yenreach/initialize.php");
    
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        $mail = new MailPasswords();
        
        $mail->user_type = !empty($post->user_type) ? (string)$post->user_type : "General";
        $mail->user_string = !empty($post->user_string) ? (string)$post->user_string : "";
        $mail->email = !empty($post->email) ? (string)$post->email : "";
        $mail->password = !empty($post->password) ? (string)$post->password : "";
        $mail->incoming_server = !empty($post->incoming_server) ? (string)$post->incoming_server : "";
        $mail->outgoing_server = !empty($post->outgoing_server) ? (string)$post->outgoing_server : "";
        $mail->smtp_port = !empty($post->smtp_port) ? (string)$post->smtp_port : "";
        $mail->pop3_port = !empty($post->pop3_port) ? (string)$post->pop3_port : "";
        $mail->imap_port = !empty($post->imap_port) ? (string)$post->imap_port : "";
        
        if($mail->insert('create')){
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                    'id' => $mail->id,
                    'user_type' => $mail->user_type,
                    'user_string' => $mail->user_string,
                    'verify_string' => $mail->verify_string,
                    'email' => $mail->email,
                    'password' => $mail->password,
                    'incoming_server' => $mail->incoming_server,
                    'outgoing_server' => $mail->outgoing_server,
                    'smtp_port' => $mail->smtp_port,
                    'pop3_port' => $mail->pop3_port,
                    'imap_port' => $mail->imap_port,
                    'created' => $mail->created,
                    'last_updated' => $mail->last_updated
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = join('<br />', $mail->errors);
        } 
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = "No input was provided";
    }
    
    $result = json_encode($return_array);
    echo $result;
?>