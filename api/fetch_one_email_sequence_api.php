<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $id = !empty($_GET['id']) ? (string)$_GET['id'] : "";
    if($id){
        $email = EmailList::find_by_id($id);
        if(!empty($email)){
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                    'id' => $email->id,
                    'title' => $email->title,
                    'content' => $email->content,
                    'created_at' => $email->created,
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Email was found';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No ID was provided';
    }

    $result = json_encode($return_array);
    echo $result;
?>