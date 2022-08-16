<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $business_string = !empty($post->business_string) ? (string)$post->business_string : "";
        $payment_plan = !empty($post->payment_plan) ? (string)$post->payment_plan : "";
        $admin_string = !empty($post->user_string) ? (string)$post->user_string : "";
        
        if(!empty($admin_string)){
            $admin = Admins::find_by_verify_string($admin_string);
            if(!empty($admin)){
                if($admin->activation == 2){
                    if(!empty($business_string)){
                        $business = Businesses::find_by_verify_string($business_string);
                        if(!empty($business)){
                            if($business->reg_stage >= 4){
                                if(!empty($payment_plan)){
                                    $plan = SubscriptionPaymentPlans::find_by_verify_string($payment_plan);
                                    if(!empty($plan)){
                                        $subscription = BusinessSubscriptions::find_by_verify_string($plan->subscription_string);
                                        if(!empty($subscription)){
                                            $user = Users::find_by_verify_string($business->user_string);
                                            if(!empty($user)){
                                                $time = time();
                                                $subscribe = new Subscribers();
                                                
                                                $subscribe->user_type = "user";
                                                $subscribe->user_string = $user->verify_string;
                                                $subscribe->business_string = $business->verify_string;
                                                $subscribe->subscription_string = $subscription->verify_string;
                                                $subscribe->paymentplan_string = $plan->verify_string;
                                                $subscribe->duration_type = $plan->duration_type;
                                                $subscribe->duration = $plan->duration;
                                                $subscribe->started = $time;
                                                $subscribe->status = 1;
                                                $subscribe->auto_renew = 0;
                                                $subscribe->agent = "admin";
                                                $subscribe->agent_string = $admin->verify_string;
                                                $subscribe->payment_method = "Admin";
                                                $subscribe->amount_paid = $plan->price;
                                                if($subscribe->subscribe()){
                                                    $return_array['status'] = 'success';
                                                    $return_array['data'] = array(
                                                            'id' => $subscribe->id,
                                                            'verify_string' => $subscribe->verify_string,
                                                            'user_string' => $subscribe->user_string,
                                                            'business_string' => $subscribe->business_string,
                                                            'subscription_string' => $subscribe->subscription_string,
                                                            'subscription' => $subscription->package,
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
                                                        
                                                    $subject = "Business Subscription";
                                                    $content = '<i>Hi '.$user->name.'</i>';
                                                    $content .= '<p>';
                                                    $content .=     "This is to inform you that your Business - {$business->name} - has been subscribed to the {$subscription->package} Subscription Package. ";
                                                    $content .=     "Please find below the details of the Subscription Plan";
                                                    $content .= '</p>';
                                                    $content .= '<p>';
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
                                                } else {
                                                    $return_array['status'] = 'failed';
                                                    $return_array['message'] = join(' ', $subscribe->errors);
                                                }
                                            } else {
                                                $return_array['status'] = 'failed';
                                                $return_array['message'] = 'No User was fetched';
                                            }
                                        } else {
                                            $return_array['status'] = 'failed';
                                            $return_array['message'] = 'No Subscription Package was fetched';
                                        }
                                    } else {
                                        $return_array['status'] = 'failed';
                                        $return_array['message'] = 'No Subscription Payment Plan was fetched';
                                    }
                                } else {
                                    $return_array['status'] = 'failed';
                                    $return_array['message'] = 'No Subscription Payment Plan was provided';
                                }
                            } else {
                                $return_array['status'] = 'failed';
                                $return_array['message'] = 'Business has to be Approved before you can subscribe them';
                            }
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = 'No Business was fetched';
                        }
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = 'No means of Business Identification';
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'Admin is not Activated';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Admin was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Admin Identification';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>