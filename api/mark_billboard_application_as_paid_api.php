<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $application = BillboardApplications::find_by_verify_string($verify_string);
            if(!empty($application)){
                $application->agent_type = !empty($post->agent_type) ? (string)$post->agent_type : "self";
                $application->agent_string = !empty($post->agent_string) ? (string)$post->agent_string : "self";
                $application->start_date = $application->proposed_start_date;
                
                $type = AdvertPaymentTypes::find_by_verify_string($application->advert_type);
                if(!empty($type)){
                    if($type->duration_type == 1){
                        $added_duration = 60 * 60 * 24 * $type->duration;
                        $expired = $added_duration + strtotime($application->start_date);
                        $application->end_date = strftime("%Y-%m-%d", $expired);
                    } elseif($type->duration_type == 2){
                        $added_duration = 60 * 60 * 24 * 7 * $type->duration;
                        $expired = $added_duration + strtotime($application->start_date);
                        $application->end_date = strftime("%Y-%m-%d", $expired);
                    } elseif($type->duration_type  == 4){
                        $started = strtotime($application->start_date);
                        $current_year = strftime("%Y", $started);
                        $int_year = (int)$current_year;
                        $next_year = $int_year + $type->duration;
                        $month = strftime('%m', $started);
                        $dating = strftime('%d', $started);
                        if(($month == '02') && ($dating == 29) && ($duration % 4 != 0)){
                            $new_dating = 28;
                            $application->end_date = $next_year.'-'.$month.'-'.$new_dating;
                        } else {
                            $application->end_date = $next_year.'-'.$month.'-'.$dating;
                        }
                    } elseif($type->duration_type == 3){
                        $started = strtotime($application->start_date);
                        $current_year = strftime('%Y', $started);
                        $int_year = (int)$current_year;
                        $current_month = strftime('%m', $started);
                        $int_month = (int)$current_month;
                        $dating = strftime('%d', $started);
                        $int_dating = (int)$dating;
                        $proposed_month = $int_month + $type->duration;
                        if($proposed_month > 12){
                            $props_month = $proposed_month - 12;
                            $new_year = $int_year + 1;
                        } else {
                            $props_month = $proposed_month;
                            $new_year = $int_year;
                        }
                        $str_month = (string)$props_month;
                        if(strlen($str_month) < 2){
                            $new_month = '0'.$str_month;
                        } else {
                            $new_month = $str_month;
                        }
                        if($new_month == '02'){
                            if((($new_year % 4) == 0) && ($dating > 29)){
                                $new_dating = 29;
                            } elseif((($new_year % 4) != 0) && ($dating > 28)){
                                $new_dating = 28;
                            } else {
                                $new_dating = $dating;
                            }
                        } elseif((($new_month == '04') || ($new_month == '06') || ($new_month == '09') || ($new_month == '11')) && ($dating > 30)){
                            $new_dating = 30;
                        } else {
                            $new_dating = $dating;
                        }
                        $application->end_date = $new_year.'-'.$new_month.'-'.$new_dating;
                    }
                    $application->stage = 4;
                    
                    if($application->insert()){
                        $user = Users::find_by_verify_string($application->user_string);
                        
                        $return_array['status'] = 'success';
                        $return_array['data'] = $application;
                        
                        $subject = "Yenreach Billboard Application - {$application->code}";
                        $content = 'Hello <i>'.$user->name.'</i>;';
                        $content .= '<p>';
                        $content .=     'This is to inform you that your application for a space on the Yenreach Billboard has been activated.';
                        $content .= '</p>';
                        $content .= '<p>';
                        $content .=     'The Advert will run from <b>'.$application->start_date.'</b> to <b>'.$application->end_date.'</b>';
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
                    $return_array['message'] = 'No Advert Payment was fetched';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Billboard Application was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No means of Identification';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>