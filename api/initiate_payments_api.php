<?php
    require_once('../../includes_yenreach/initialize.php');
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $user_type = !empty($post->user_type) ? (string)$post->user_type : "";
        $user_string = !empty($post->user_string) ? (string)$post->user_string : "";
        if($user_type == "user"){
            if(!empty($user_string)){
                $user = Users::find_by_verify_string($user_string);
                if(!empty($user)){
                    $reason = !empty($post->reason) ? (string)$post->reason : "";
                    if(!empty($reason)){
                        $subject = !empty($post->subject) ? (string)$post->subject : "";
                        if(!empty($subject)){
                            $platform = !empty($post->platform) ? (string)$post->platform : "";
                            if(!empty($platform)){
                                if($reason == "business_subscription"){
                                    $sub_payment = SubscriptionPayments::find_by_verify_string($subject);
                                    if(!empty($sub_payment)){
                                        $plan = SubscriptionPaymentPlans::find_by_verify_string($sub_payment->paymentplan_string);
                                        if(!empty($plan)){
                                            $subscription = BusinessSubscriptions::find_by_verify_string($plan->subscription_string);
                                            if(!empty($subscription)){
                                                $business = Businesses::find_by_verify_string($sub_payment->business_string);
                                                if(!empty($business)){
                                                    $amount = $plan->price;
                                                    $description = "{$subscription->package} Subscription Package ({$plan->plan}) for {$business->name}";
                                                    $phone = $business->phonenumber;
                                                } else {
                                                    $amount = NULL;
                                                    $message = "No Business was fetched";
                                                }
                                            } else {
                                                $amount = NULL;
                                                $message = "No Subscription Package was fetched";
                                            }
                                        } else {
                                            $amount = NULL;
                                            $message = "No Payment Plan was fetched";
                                        }
                                    } else {
                                        $amount = NULL;
                                        $message = "No Initialised Subscription Payement";
                                    }
                                } elseif($reason == "business_subscription_renewal"){
                                    $sub_payment = SubscriptionPayments::find_by_verify_string($subject);
                                    if(!empty($sub_payment)){
                                        $plan = SubscriptionPaymentPlans::find_by_verify_string($sub_payment->paymentplan_string);
                                        if(!empty($plan)){
                                            $subscription = BusinessSubscriptions::find_by_verify_string($plan->subscription_string);
                                            if(!empty($subscription)){
                                                $business = Businesses::find_by_verify_string($sub_payment->business_string);
                                                if(!empty($business)){
                                                    $amount = $plan->price;
                                                    $description = "{$subscription->package} Subscription Package Renewal ({$plan->plan}) for {$business->name}";
                                                    $phone = $business->phonenumber;
                                                } else {
                                                    $amount = NULL;
                                                    $message = "No Business was fetched";
                                                }
                                            } else {
                                                $amount = NULL;
                                                $message = "No Subscription Package was fetched";
                                            }
                                        } else {
                                            $amount = NULL;
                                            $message = "No Payment Plan was fetched";
                                        }
                                    } else {
                                        $amount = NULL;
                                        $message = "No Initialised Subscription Payement";
                                    }
                                } elseif($reason == "billboard_payment"){
                                    $application = BillboardApplications::find_by_verify_string($subject);
                                    if(!empty($application)){
                                        $advert = AdvertPaymentTypes::find_by_verify_string($application->advert_type);
                                        if(!empty($advert)){
                                            $amount = $advert->amount;
                                            $description = "Payment of {$advert->title} Yenreach Billboard Package for Application - {$application->code}";
                                            $phone = "";
                                        } else {
                                            $amount = NULL;
                                            $message = "No Advert Payment was fetched";
                                        }
                                    } else {
                                        $amount = NULL;
                                        $message = "No Billboard Application was fetched";
                                    }
                                }
                                
                                if($amount !== NULL){
                                    $money = new MoneyRecieveds();
                                    $money->user_type = $user_type;
                                    $money->user_string = $user_string;
                                    $money->reason = $reason;
                                    $money->subject = $subject;
                                    $money->platform = $platform;
                                    $money->currency = "NGN";
                                    $money->amount = $amount;
                                    
                                    if($money->insert()){
                                        $tx_ref = $money->tx_ref;
                                        if($money->platform == 'Flutterwave'){
                                            $purl = 'https://api.flutterwave.com/v3/payments';
                                            $payload = [
                                                    'tx_ref' => $tx_ref,
                                                    'currency' => $money->currency,
                                                    'amount' => $money->amount,
                                                    'redirect_url' => 'http://127.0.0.1:5173/users/verify_payment',
                                                    'payment_options' => 'card',
                                                    'customer' => array(
                                                            'email' => $user->email,
                                                            'phonenumber' => $phone,
                                                            'name' => $user->name
                                                        ),
                                                    'customizations' => array(
                                                            'title' => 'Yenreach Payments',
                                                            'description' => $description,
                                                            'logo' => 'https://yenreach.com/assets/img/logo.png'
                                                        )
                                                ];
                                            $initiate = rave_post_curl($purl, $payload);
                                            if($initiate){
                                                $response = json_encode($initiate);
                                                $money->response1 = $response;
                                                $money->status = 1;
                                                $money->insert();
                                                if($initiate->status == "success"){
                                                    $subject = "Yenreach Payment - ".$money->tx_ref;
                                                    $content = '<i>Hi '.$user->name.',</i>';
                                                    $content .= '<p>';
                                                    $content .=     'You are about to make a Payment on the <a href="https://yenreach.com">Yenreach</a>.';
                                                    $content .= '</p>';
                                                    $content .= '<p>';
                                                    $content .=     '<b>Payment Platform:</b> '.$money->platform.'<br />';
                                                    $content .=     '<b>Transaction Reference: </b>'.$money->tx_ref.'<br />';
                                                    $content .=     '<b>Reason:</b> '.strtoupper($money->reason).'<br />';
                                                    $content .=     '<b>Subject:</b> '.$description.'<br />';
                                                    $content .=     '<b>Amount:</b> '.$money->currency.number_format($money->amount, 2);
                                                    $content .= '</p>';
                                                    $content .= '<p>The status of your Payment will be sent to you shortly<br />For further enquiries, you can send a mail to <a href="mailto:support@yenreach.com">';
                                                    $content .= 'support@yenreach.com</a></p>';
                                                    $content .= '<p><i>Yenreach Team</i></p>';
                                                    
                                                    $spurl = 'send_mail_api.php';
                                                    $spdata = [
                                                            'ticket_id' => '',
                                                            'movement' => 'outgoing',
                                                            'from_name' => 'Yenreach Support',
                                                            'from_mail' => 'support@yenreach.com',
                                                            'recipient_name' => $user->name,
                                                            'recipient_mail' => $user->email,
                                                            'subject' => $subject,
                                                            'content' => $content,
                                                            'reply_name' => 'Yenreach Support',
                                                            'reply_mail' => 'support@yenreach.com'
                                                        ];
                                                        
                                                    perform_post_curl($spurl, $spdata);
                                                    
                                                    $return_url = $initiate->data->link;
                                                    
                                                    $return_array['status'] = 'success';
                                                    $return_array['data'] = array(
                                                            'id' => $money->id,
                                                            'verify_string' => $money->verify_string,
                                                            'user_type' => $money->user_type,
                                                            'user_string' => $money->user_string,
                                                            'reason' => $money->reason,
                                                            'subject' => $money->subject,
                                                            'amount' => $money->amount,
                                                            'platform' => $money->platform,
                                                            'currency' => $money->currency,
                                                            'url' => $return_url
                                                        );
                                                    
                                                } else {
                                                    $return_array['status'] = 'failed';
                                                    $return_array['message'] = $initiate->message.": ";
                                                }
                                            } else {
                                                $return_array['status'] = 'failed';
                                                $return_array['message'] = 'Payment Platform Initiation failed';
                                            }
                                        } else {
                                            $return_array['status'] = 'failed';
                                            $return_array['message'] = 'No Payment Plarform was provided';
                                        }
                                    } else {
                                        $return_array['status'] = 'failed';
                                        $return_array['message'] = join(' ', $money->errors);
                                    }
                                } else {
                                    $return_array['status'] = 'failed';
                                    $return_array['message'] = $message;
                                }
                            } else {
                                $return_array['status'] = 'failed';
                                $return_array['message'] = 'No Payment Platform was provided';
                            }
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = 'No Subject was provided';
                        }
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = 'No Reason was given';
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No User was fetched';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No mode of User Identification';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'User Type was not provided';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>