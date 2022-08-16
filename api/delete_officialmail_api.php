<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $mail = MailPasswords::find_by_verify_string($string);
        if(!empty($mail)){
            if($mail->delete()){
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
                $return_array['message'] = 'Official Mail Deleting failed';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Official Mail was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>