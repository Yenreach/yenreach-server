<?php
    require_once("../../includes_yenreach/initialize.php");
    
    $return_array = array();
    
    $mails = MailPasswords::find_all();
    if(!empty($mails)){
        $data_array = array();
        foreach($mails as $mail){
            $data_array[] = array(
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
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = "No Email was fetched";
    }
    
    $result = json_encode($return_array);
    echo $result;
?>