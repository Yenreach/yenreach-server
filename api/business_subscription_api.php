<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $verify_string = !empty($post->verify_string) ? (string)$post->verify_string : "";
        if(!empty($verify_string)){
            $sub_payment = SubscriptionPayments::find_by_verify_string($verify_string);
            if(!empty($sub_payment)){
                $subscribe = new Subscribers();
                
                $subscribe->user_type = $sub_payment->user_type;
                $subscribe->user_string = $sub_payment->user_string;
                $subscribe->business_string = $sub_payment->business_string;
                $subscribe->paymentplan_string = $sub_payment->paymentplan_string;
                $plan = SubscriptionPaymentPlans::find_by_verify_string($sub_payment->paymentplan_string);
                $user = Users::find_by_verify_string($sub_payment->user_string);
                $business = Businesses::find_by_verify_string($sub_payment->business_string);
                if(!empty($plan)){
                    $time = time();
                    $subscribe->subscription_string = $plan->subscription_string;
                    $subscribe->duration_type = $plan->duration_type;
                    $subscribe->duration = $plan->duration;
                    $subscribe->started = $time;
                    $subscribe->auto_renew = 1;
                    $subscribe->status = 1;
                    $subscribe->agent = "user";
                    $subscribe->agent_string = $user->verify_string;
                    $subscribe->payment_method = !empty($post->payment_method) ? (string)$post->payment_method : "";
                    $subscribe->amount_paid = !empty($post->amount_paid) ? (double)$post->amount_paid : "";
                    if($subscribe->subscribe()){
                        $sub_payment->status = 3;
                        $sub_payment->insert();

                        $business->subscription_string = $sub_payment->subscription_string;
                        $business->insert();       
                               
                        $return_array['status'] = 'success';
                        $return_array['data'] = array(
                                'id' => $subscribe->id,
                                'verify_string' => $subscribe->verify_string,
                                'user_string' => $subscribe->user_string,
                                'business_string' => $subscribe->business_string,
                                'subscription_string' => $subscribe->subscription_string,
                                'paymentplan_string' => $subscribe->paymentplan_string,
                                'amount_paid' => $subscribe->amount_paid,
                                'duration_type' => $subscribe->duration_type,
                                'duration' => $subscribe->duration,
                                'started' => $subscribe->started,
                                'expired' => $subscribe->expired,
                                'true_expiry' => $subscribe->true_expiry,
                                'status' => $subscribe->status,
                                'payment_method' => $subscribe->payment_method,
                                'auto_renew' => $subscribe->auto_renew,
                                'agent_type' => $subscribe->agent_type,
                                'agent_string' => $subscribe->agent_string,
                                'created' => $subscribe->created,
                                'last_updated' => $subscribe->last_updated
                            );
                        
                        $business = Businesses::find_by_verify_string($subscribe->business_string);
                        $subscription = BusinessSubscriptions::find_by_verify_string($plan->subscription_string);
                        $subject = "Yenreach Subscription";
                        $content = '<i>Hi '.$user->name.'</i>,';
                        $content .= '<p>';
                        $content .=     'Congratulations!';
                        $content .=     'Your Subscription on <a href="https://yenreach.com">Yenreach</a> for your Business, ';
                        $content .=     "<br />".$business->name."<br /> was successful.";
                        $content .= '</p>';
                        $content .= '<p>';
                        $content .=     'Please find below the details of the Suscription Plan';
                        $content .=     '<table border="1">';
                        $content .=         '<tr>';
                        $content .=             '<td>Subscription Package</td><td>'.$subscription->package.'</td>';
                        $content .=         '</tr>';
                        $content .=         '<tr>';
                        $content .=             '<td>Starting Time: </td><td>'.strftime("%Y.%m.%d %H:%M:%S", $subscribe->started).'</td>';
                        $content .=         '</tr>';
                        $content .=         '<tr>';
                        $content .=             '<td>Expiry Time: </td><td>'.strftime("%Y.%m.%d %H:%M:%S", $subscribe->expired).'</td>';
                        $content .=         '</tr>';
                        $content .=     '</table>';
                        $content .= '</p>';
                        $content .= '<p>';
                        $content .=     'For further information, enquiries or complaints, kindly email our Support Team at <a href="mailto:support@yenreach.com">support@yenreach.com</a>';
                        $content .=     '<br />Kind Regards.<br />';
                        $content .=     '<i>Yenreach Team</i>';
                        $content .= '</p>';
                        
                        $purl = "send_mail_api.php";
                        $pdata = [
                                'ticket_id' => '',
                                'movement' => 'outgoing',
                                'from_name' => 'Yenreach Support',
                                'from_mail' => 'support@yenreach.com',
                                'recipient_name' => $user->name,
                                'recipient_mail' => $user->email,
                                'recipient_cc_name' => $business->name,
                                'recipient_cc' => $business->email,
                                'subject' => $subject,
                                'content' => $content,
                                'reply_name' => 'Yenreach Support',
                                'reply_mail' => 'support@yenreach.com'
                            ];
                            
                        perform_post_curl($purl, $pdata);
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Payment Plan was fetched';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Subscription Initialisatio Data was fetched';
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