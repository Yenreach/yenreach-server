<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $application = BillboardApplications::find_by_verify_string($string);
        if(!empty($application)){
            $application->stage = 3;
            if($application->insert()){
                $user = Users::find_by_verify_string($application->user_string);
                $advert = AdvertPaymentTypes::find_by_verify_string($application->advert_string);
                
                $return_array['status'] = 'success';
                $return_array['data'] = $application;
                
                $subject = "Yenreach Billboard Application - {$application->code}";
                $content = 'Hello <i>'.$user->name.'</i>;';
                $content .= '<p>';
                $content .=     'This is to inform you that your application for a space on the Yenreach Billboard has been approved.';
                $content .= '</p>';
                $content .= '<p>';
                $content .=     'In order to activate the advert, please proceed to <a href="https://yenreach.com/users/billboard_applied?'.$application->verify_string.'">https://yenreach.com/users/billboard_applied?'.$application->verify_string.'</a> ';
                $content .=     'to pay for the Advert';
                $content .=     '<br />';
                $content .=     'Please note that the payment must be made on or before '.$application->proposed_start_date.'. Failure to make the payment at the said date will automatically cancel this Application';
                $content .= '</p>';
                $content .= '<p>';
                $content .=     'For further enquiries or complaints, you can send a mail to <a href="mailto:support@yenreach.com">support@yenreach.com</a>';
                $content .= '</p>';
                $content .= '<p>';
                $content .=     '<i>Yenreach Support Team</i>';
                $content .= '</p>';
                
                $spurl = "send_mail_api.php";
                $spdata = [
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
                
                perform_post_curl($spurl, $spdata);
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = join(' ', $application->errors);
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Billboard Application was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>