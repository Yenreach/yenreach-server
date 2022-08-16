<?php
    require_once('../../includes_yenreach/initialize.php');
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        $transaction = MoneyRecieveds::find_by_verify_string($string);
        if(!empty($transaction)){
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                    'id' => $transaction->id,
                    'verify_string' => $transaction->verify_string, 
                    'platform' => $transaction->platform,
                    'tx_ref' => $transaction->tx_ref,
                    'user_type' => $transaction->user_type,
                    'user_string' => $transaction->user_string,
                    'reason' => $transaction->reason,
                    'subject' => $transaction->subject,
                    'currency' => $transaction->currency,
                    'amount' => $transaction->amount,
                    'response1' => $transaction->response1,
                    'response2' => $transaction->response2,
                    'response3' => $transaction->response3,
                    'response4' => $transaction->response4,
                    'response5' => $transaction->response5,
                    'status' => $transaction->status,
                    'created' => $transaction->created,
                    'last_updated' => $transaction->last_updated
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Transaction was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No mode of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>