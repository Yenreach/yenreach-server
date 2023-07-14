<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $emails = EmailList::find_all();
    if(!empty($emails)){
        $data_array = array();
        foreach($emails as $email){
            $data_array[] = array(
                'id' => $email->id,
                'title' => $email->title,
                'content' => $email->content,
                'created_at' => $email->created,
            );
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Blog Post was fetched';
    }

    $result = json_encode($return_array);
    echo $result;
?>