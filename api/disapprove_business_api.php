<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $business = Businesses::find_by_verify_string($verify_string);
            if(!empty($business)){
                $remarks = !empty($post->remarks) ? (string)$post->remarks : "";
                if(!empty($remarks)){
                    $business->reg_stage = 0;
                    $business->remarks = $remarks;
                    if($business->insert()){
                        $user = Users::find_by_verify_string($business->user_string);
                        if(!empty($user)){
                            $owner_name = $user->name;
                            $owner_email = $user->email;
                        } else {
                            $owner_name = "";
                            $owner_email = "";
                        }
                        $return_array['status'] = 'success';
                        $return_array['data'] = array(
                                'id' => $business->id, 
                                'verify_string' => $business->verify_string, 
                                'name' => $business->name, 
                                'description' => $business->description,
                                'user_string' => $business->user_string, 
                                'subscription_string' => $business->subscription_string,
                                'category' => $business->category,
                                'address' => $business->address,
                                'town' => $business->town,
                                'lga' => $business->lga,
                                'state' => $business->state,
                                'state_id' => $business->state_id,
                                'phonenumber' => $business->phonenumber,
                                'whatsapp' => $business->whatsapp,
                                'email' => $business->email,
                                'website' => $business->website, 
                                'facebook_link' => $business->facebook_link, 
                                'twitter_link' => $business->twitter_link,
                                'instagram_link' => $business->instagram_link,
                                'youtube_link' => $business->youtube_link,
                                'linkedin_link' => $business->linkedin_link,
                                'working_hours' => $business->working_hours,
                                'cv' => $business->cv,
                                'modifiedby' => $business->modifiedby,
                                'experience' => $business->experience,
                                'month_started' => $business->month_started,
                                'year_started' => $business->year_started,
                                'reg_stage' => $business->reg_stage,
                                'remarks' => $business->remarks,
                                'activation' => $business->activation,
                                'filename' => $business->filename, 
                                'created' => $business->created,
                                'last_updated' => $business->last_updated
                            );
                        
                        $subject = "Business Disapproval";
                        $content = '<i>'.$owner_name.',</i>';
                        $content .= '<p>';
                        $content .=     'We regret to inform you that the registration pf your Business, <b>'.$business->name.'</b>, has been disapproved. Please ';
                        $content .=     'find below the reason behind the Disapproval:';
                        $content .= '</p>';
                        $content .= '<p><b>'.nl2br($remarks).'</b></p>';
                        $content .= '<p>';
                        $content .=     'For further enquiries, you can reach us at <a href="mailto:support@yenreach.com">support@yenreach.com</a><br />';
                        $content .=     '<i>The Yenreach Team</i>';
                        $content .= '</p>';
                        
                        $purl = "send_mail_api.php";
                        $pdata = [
                                'ticket_id' => '',
                                'movement' => 'outgoing',
                                'from_name' => 'Yenreach',
                                'from_mail' => 'info@yenreach.com',
                                'recipient_name' => $owner_name,
                                'recipient_mail' => $owner_email,
                                'recipient_cc_name' => $business->name,
                                'recipient_cc' => $business->email,
                                'subject' => $subject,
                                'content' => $content,
                                'reply_name' => 'Yenreach',
                                'reply_mail' => 'info@yenreach.com'
                            ];
                            
                        perform_post_curl($purl, $pdata);
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = join(' ', $business->errors);
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'Disapproval Remarks must be given';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Business was fetched';
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