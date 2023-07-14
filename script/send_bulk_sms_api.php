<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();

    $post_json = @file_get_contents("php://input");

    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $admin_string = !empty($post->admin_string) ? (string)$post->admin_string : "";

        if($admin_string){
            $admin = Admins::find_by_verify_string($admin_string);
            if(!empty($admin)){
                $messagetext = !empty($post->message) ? (string)$post->message : "";
                if($messagetext){
                    $businesses = Businesses::find_all();
                    $numbers = '';
                    $count = 0;

                    foreach($businesses as $business){
                        if($count == 500){
                            break;
                        }
                        if($business->phonenumber){
                            $numbers .= $business->phonenumber.',';
                            $count++;
                        }                   
                    }

                    $username = "conceptdordorian@gmail.com";
                    $apikey = "4b33defd88cf5707b833c4ceaa5627c550ce68ff";
                    $flash = 0;
                    $sendername = "YENREACH";
                    $recipients = $numbers;

                    $bulksms = new BulkSms(); 

                    $response = $bulksms->useHTTPGet($username, $apikey, $flash, $sendername, $messagetext, $recipients);

                    $return_array['status'] = 'success';
                    $return_array['message'] = 'Bulk Sms sent successfully : '.$response;
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'Message is empty';
                }
                
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Admin was found';
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