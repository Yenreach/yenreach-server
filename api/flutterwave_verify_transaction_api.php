<?php
    require_once('../../includes_yenreach/initialize.php');
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $tx_ref = !empty($post->txref) ? (string)$post->txref : "";
        if(!empty($tx_ref)){
            $transaction = MoneyRecieveds::find_by_tx_ref($tx_ref);
            if(!empty($transaction)){
                $payment_data = !empty($post->data) ? (string)$post->data : "";
                $transaction->response2 = $payment_data;
                $transaction->status = 2;
                if($transaction->insert()){
                    $transid = !empty($post->tranx_id) ? (string)$post->tranx_id : "";
                    $gurl = 'https://api.flutterwave.com/v3/transactions/'.$transid.'/verify';
                    $verify = rave_get_curl($gurl);
                    if($verify){
                        $verify_json = json_encode($verify);
                        $transaction->response3 = $verify_json;
                        if($transaction->insert()){
                            if($verify->status == "success"){
                                $data = $verify->data;
                                if(($data->tx_ref == $transaction->tx_ref) && ($data->currency == $transaction->currency) && ($data->amount == $transaction->amount) && ($data->status == 'successful')){
                                    $transaction->status = 3;
                                    if($transaction->insert()){
                                        if($transaction->user_type == 'user'){
                                            $user = Users::find_by_verify_string($transaction->user_string);
                                            if(!empty($user)){
                                                if($transaction->reason == "business_subscription"){
                                                    $sub_payment = SubscriptionPayments::find_by_verify_string($transaction->subject);
                                                    if(!empty($sub_payment)){
                                                        $plan = SubscriptionPaymentPlans::find_by_verify_string($sub_payment->paymentplan_string);
                                                        $amount = $data->amount;
                                                        $realamount = (double)$amount;
                                                        if($realamount >= $plan->price){
                                                            $auth = $data->card;
                                                            
                                                            $subscription = BusinessSubscriptions::find_by_verify_string($sub_payment->subscription_string);
                                                            if(!empty($subscription)){
                                                                $business = Businesses::find_by_verify_string($sub_payment->business_string);
                                                                if(!empty($business)){
                                                                    $sub_payment->status = 2;
                                                                    $sub_payment->insert();
                                                                    
                                                                    $cardtoken = CardTokens::find_by_token($transaction->platform, $transaction->user_type, $transaction->user_string, $auth->token);
                                                                    if(empty($cardtoken)){
                                                                        $cardtoken = new CardTokens();
                                                                        $cardtoken->platform = $transaction->platform;
                                                                        $cardtoken->user_type = $transaction->user_type;
                                                                        $cardtoken->user_string = $transaction->user_string;
                                                                        $cardtoken->card_digits = $auth->first_6digits."***".$auth->last_4digits;
                                                                        $cardtoken->token = $auth->token;
                                                                        $cardtoken->issuer = $auth->issuer;
                                                                        $cardtoken->card_type = $auth->type;
                                                                        $cardtoken->expiry = $auth->expiry;
                                                                        $cardtoken->insert();
                                                                    }
                                                                    
                                                                    $return_array['status'] = 'success';
                                                                    $return_array['data'] = array(
                                                                            'id' => $transaction->id,
                                                                            'verify_string' => $transaction->verify_string,
                                                                            'user_type' => $transaction->user_type,
                                                                            'user_string' => $transaction->user_string,
                                                                            'reason' => $transaction->reason,
                                                                            'subject' => $transaction->subject,
                                                                            'amount' => $transaction->amount,
                                                                            'created' => $transaction->created,
                                                                            'last_updated' => $transaction->last_updated,
                                                                            'status' => $transaction->status
                                                                        );
                                                                    
                                                                    $subject = "Yenreach Payment - ".$transaction->tx_ref;
                                                                    $content = '<i>Hi '.$user->name.',</i>';
                                                                    $content .= "<p>Your Account was successfully charged by <a href=\"https://yenreach.com\">Yenreach Platform</a></p>";
                                                                    $content .= "<p>";
                                                                    $content .=     "<h5>Payment Details</h5>";
                                                                    $content .=     "<b>Payment Platform: </b>".$transaction->platform."<br />";
                                                                    $content .=     "<b>Reason: </b>".strtoupper($transaction->reason)."<br />";
                                                                    $content .=     "<b>Subject: </b> {$subscription->package} Subscription Package ({$plan->plan}) for {$business->name}";
                                                                    $content .=     "<b>Amount: </b>".$transaction->currency.number_format($transaction->amount, 2)."<br />";
                                                                    $content .=     "<b>Card Type: </b>".strtoupper($auth->type)."<br />";
                                                                    $content .=     "<b>Card Number: </b>".$auth->first_6digits."***".$auth->last_4digits."<br />";
                                                                    $content .=     "<b>Expiry: </b>".$auth->expiry."<br />";
                                                                    $content .=     "<b>Bank: </b>".$auth->issuer;
                                                                    $content .= "</p>";
                                                                    $content .= "<p>For further enquiries, you can send a mail to <a href=\"mailto:support@yenreach.com\">support@yenreach.com</a>";
                                                                    $content .= "<br />Thanks<br />Yenreach Tech Team</p>";
                                                                    
                                                                    $spurl = 'send_mail_api.php';
                                                                    $spdata = [
                                                                            'ticket_id' => '',
                                                                            'movement' => 'outgoing',
                                                                            'from_name' => 'Yenreach Tech Support',
                                                                            'from_mail' => 'support@yenreach.com',
                                                                            'recipient_name' => $user->name,
                                                                            'recipient_mail' => $user->email,
                                                                            'subject' => $subject,
                                                                            'content' => $content,
                                                                            'reply_name' => 'Yenreach Tech Support',
                                                                            'reply_mail' => 'support@yenreach.com'
                                                                        ];
                                                                        
                                                                    perform_post_curl($spurl, $spdata);
                                                                } else {
                                                                    $return_array['status'] = 'failed';
                                                                    $return_array['message'] = 'No Business was fetched';
                                                                }
                                                            } else {
                                                                $return_array['status'] = 'failed';
                                                                $return_array['message'] = 'No Business Subscription was fetched';
                                                            }
                                                        } else {
                                                            $return_array['status'] = 'failed';
                                                            $return_array['message'] = 'The Amount paid was less than the Subscription Fee';
                                                        }
                                                    } else {
                                                        $return_array['status'] = 'failed';
                                                        $return_array['message'] = 'No Subscription Payment Initialisation was fetched';
                                                    }
                                                } elseif($transaction->reason == "business_subscription_renewal"){
                                                    $sub_payment = SubscriptionPayments::find_by_verify_string($transaction->subject);
                                                    if(!empty($sub_payment)){
                                                        $plan = SubscriptionPaymentPlans::find_by_verify_string($sub_payment->paymentplan_string);
                                                        $amount = $data->amount;
                                                        $realamount = (double)$amount;
                                                        if($realamount >= $plan->price){
                                                            $auth = $data->card;
                                                            
                                                            $subscription = BusinessSubscriptions::find_by_verify_string($sub_payment->subscription_string);
                                                            if(!empty($subscription)){
                                                                $business = Businesses::find_by_verify_string($sub_payment->business_string);
                                                                if(!empty($business)){
                                                                    $sub_payment->status = 2;
                                                                    $sub_payment->insert();
                                                                    
                                                                    $cardtoken = CardTokens::find_by_token($transaction->platform, $transaction->user_type, $transaction->user_string, $auth->token);
                                                                    if(empty($cardtoken)){
                                                                        $cardtoken = new CardTokens();
                                                                        $cardtoken->platform = $transaction->platform;
                                                                        $cardtoken->user_type = $transaction->user_type;
                                                                        $cardtoken->user_string = $transaction->user_string;
                                                                        $cardtoken->card_digits = $auth->first_6digits."***".$auth->last_4digits;
                                                                        $cardtoken->token = $auth->token;
                                                                        $cardtoken->issuer = $auth->issuer;
                                                                        $cardtoken->card_type = $auth->type;
                                                                        $cardtoken->expiry = $auth->expiry;
                                                                        $cardtoken->insert();
                                                                    }
                                                                    
                                                                    $return_array['status'] = 'success';
                                                                    $return_array['data'] = array(
                                                                            'id' => $transaction->id,
                                                                            'verify_string' => $transaction->verify_string,
                                                                            'user_type' => $transaction->user_type,
                                                                            'user_string' => $transaction->user_string,
                                                                            'reason' => $transaction->reason,
                                                                            'subject' => $transaction->subject,
                                                                            'amount' => $transaction->amount,
                                                                            'created' => $transaction->created,
                                                                            'last_updated' => $transaction->last_updated,
                                                                            'status' => $transaction->status
                                                                        );
                                                                    
                                                                    $subject = "Yenreach Payment - ".$transaction->tx_ref;
                                                                    $content = '<i>Hi '.$user->name.',</i>';
                                                                    $content .= "<p>Your Account was successfully charged by <a href=\"https://yenreach.com\">Yenreach Platform</a></p>";
                                                                    $content .= "<p>";
                                                                    $content .=     "<h5>Payment Details</h5>";
                                                                    $content .=     "<b>Payment Platform: </b>".$transaction->platform."<br />";
                                                                    $content .=     "<b>Reason: </b>".strtoupper($transaction->reason)."<br />";
                                                                    $content .=     "<b>Subject: </b> {$subscription->package} Subscription Package ({$plan->plan}) for {$business->name}";
                                                                    $content .=     "<b>Amount: </b>".$transaction->currency.number_format($transaction->amount, 2)."<br />";
                                                                    $content .=     "<b>Card Type: </b>".strtoupper($auth->type)."<br />";
                                                                    $content .=     "<b>Card Number: </b>".$auth->first_6digits."***".$auth->last_4digits."<br />";
                                                                    $content .=     "<b>Expiry: </b>".$auth->expiry."<br />";
                                                                    $content .=     "<b>Bank: </b>".$auth->issuer;
                                                                    $content .= "</p>";
                                                                    $content .= "<p>For further enquiries, you can send a mail to <a href=\"mailto:support@yenreach.com\">support@yenreach.com</a>";
                                                                    $content .= "<br />Thanks<br />Yenreach Tech Team</p>";
                                                                    
                                                                    $spurl = 'send_mail_api.php';
                                                                    $spdata = [
                                                                            'ticket_id' => '',
                                                                            'movement' => 'outgoing',
                                                                            'from_name' => 'Yenreach Tech Support',
                                                                            'from_mail' => 'support@yenreach.com',
                                                                            'recipient_name' => $user->name,
                                                                            'recipient_mail' => $user->email,
                                                                            'subject' => $subject,
                                                                            'content' => $content,
                                                                            'reply_name' => 'Yenreach Tech Support',
                                                                            'reply_mail' => 'support@yenreach.com'
                                                                        ];
                                                                        
                                                                    perform_post_curl($spurl, $spdata);
                                                                } else {
                                                                    $return_array['status'] = 'failed';
                                                                    $return_array['message'] = 'No Business was fetched';
                                                                }
                                                            } else {
                                                                $return_array['status'] = 'failed';
                                                                $return_array['message'] = 'No Business Subscription was fetched';
                                                            }
                                                        } else {
                                                            $return_array['status'] = 'failed';
                                                            $return_array['message'] = 'The Amount paid was less than the Subscription Fee';
                                                        }
                                                    } else {
                                                        $return_array['status'] = 'failed';
                                                        $return_array['message'] = 'No Subscription Payment Initialisation was fetched';
                                                    }
                                                } elseif($transaction->reason == "billboard_payment"){
                                                    $application = BillboardApplications::find_by_verify_string($transaction->subject);
                                                    if(!empty($application)){
                                                        $advert = AdvertPaymentTypes::find_by_verify_string($application->advert_type);
                                                        if(!empty($advert)){
                                                            $amount = $data->amount;
                                                            $real_amount = (double)$amount;
                                                            if($real_amount >= $advert->amount){
                                                                $cardtoken = CardTokens::find_by_token($transaction->platform, $transaction->user_type, $transaction->user_string, $auth->token);
                                                                if(empty($cardtoken)){
                                                                    $cardtoken = new CardTokens();
                                                                    $cardtoken->platform = $transaction->platform;
                                                                    $cardtoken->user_type = $transaction->user_type;
                                                                    $cardtoken->user_string = $transaction->user_string;
                                                                    $cardtoken->card_digits = $auth->first_6digits."***".$auth->last_4digits;
                                                                    $cardtoken->token = $auth->token;
                                                                    $cardtoken->issuer = $auth->issuer;
                                                                    $cardtoken->card_type = $auth->type;
                                                                    $cardtoken->expiry = $auth->expiry;
                                                                    $cardtoken->insert();
                                                                }
                                                                
                                                                $return_array['status'] = 'success';
                                                                $return_array['data'] = array(
                                                                        'id' => $transaction->id,
                                                                        'verify_string' => $transaction->verify_string,
                                                                        'user_type' => $transaction->user_type,
                                                                        'user_string' => $transaction->user_string,
                                                                        'reason' => $transaction->reason,
                                                                        'subject' => $transaction->subject,
                                                                        'amount' => $transaction->amount,
                                                                        'created' => $transaction->created,
                                                                        'last_updated' => $transaction->last_updated,
                                                                        'status' => $transaction->status
                                                                    );
                                                                
                                                                $subject = "Yenreach Payment - ".$transaction->tx_ref;
                                                                $content = '<i>Hi '.$user->name.',</i>';
                                                                $content .= "<p>Your Account was successfully charged by <a href=\"https://yenreach.com\">Yenreach Platform</a></p>";
                                                                $content .= "<p>";
                                                                $content .=     "<h5>Payment Details</h5>";
                                                                $content .=     "<b>Payment Platform: </b>".$transaction->platform."<br />";
                                                                $content .=     "<b>Reason: </b>".strtoupper($transaction->reason)."<br />";
                                                                $content .=     "<b>Subject: </b> {$subscription->package} Subscription Package ({$plan->plan}) for {$business->name}";
                                                                $content .=     "<b>Amount: </b>".$transaction->currency.number_format($transaction->amount, 2)."<br />";
                                                                $content .=     "<b>Card Type: </b>".strtoupper($auth->type)."<br />";
                                                                $content .=     "<b>Card Number: </b>".$auth->first_6digits."***".$auth->last_4digits."<br />";
                                                                $content .=     "<b>Expiry: </b>".$auth->expiry."<br />";
                                                                $content .=     "<b>Bank: </b>".$auth->issuer;
                                                                $content .= "</p>";
                                                                $content .= "<p>For further enquiries, you can send a mail to <a href=\"mailto:support@yenreach.com\">support@yenreach.com</a>";
                                                                $content .= "<br />Thanks<br />Yenreach Tech Team</p>";
                                                                
                                                                $spurl = 'send_mail_api.php';
                                                                $spdata = [
                                                                        'ticket_id' => '',
                                                                        'movement' => 'outgoing',
                                                                        'from_name' => 'Yenreach Tech Support',
                                                                        'from_mail' => 'support@yenreach.com',
                                                                        'recipient_name' => $user->name,
                                                                        'recipient_mail' => $user->email,
                                                                        'subject' => $subject,
                                                                        'content' => $content,
                                                                        'reply_name' => 'Yenreach Tech Support',
                                                                        'reply_mail' => 'support@yenreach.com'
                                                                    ];
                                                                    
                                                                perform_post_curl($spurl, $spdata);
                                                            } else {
                                                                $return_array['status'] = 'failed';
                                                                $return_array['message'] = 'The Amount paid was less than the Advert Fee';
                                                            }
                                                        } else {
                                                            $return_array['status'] = 'failed';
                                                            $return_array['message'] = 'No Advert Type was fetched';
                                                        }
                                                    } else {
                                                        $return_status = 'failed';
                                                        $return_array['message'] = 'No Billboard Application was fetched';
                                                    }
                                                } else {
                                                    $return_array['status'] = 'failed';
                                                    $return_array['message'] = 'No reason for transaction was given';
                                                }
                                            } else {
                                                $return_array['status'] = 'failed';
                                                $return_array['message'] = 'No User data was fetched';
                                            }
                                        } else {
                                            $return_array['status'] = 'failed';
                                            $return_array['message'] = "No User type was fetched";
                                        }
                                    } else {
                                        $return_array['status'] = 'failed';
                                        $return_array['message'] = join(' ', $transaction->errors);
                                    }
                                } else {
                                    $return_array['status'] = 'failed';
                                    $return_array['message'] = "Paystack Verification failed";
                                }
                            } else {
                                $return_array['status'] = 'failed';
                                $return_array['message'] = $verify->message;
                            }    
                        } else {
                            $return_array['status'] = 'failed';
                            $return_array['message'] = $transaction->errors;
                        }
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = 'Connection to Paystack was broken';
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $transaction->errors);
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Transaction was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No means of Transaction Identification';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>