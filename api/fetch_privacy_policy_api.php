<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $privacypolicy = PrivacyPolicy::find_all();
    if(!empty($privacypolicy)){
        $data_array = array();
        foreach($privacypolicy as $item){
            $data_array[] = array(
                'id' => $item->id,
                'content' => $item->content,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            );
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array[0];
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Blog Post was fetched';
    }

    $result = json_encode($return_array);
    echo $result;
?>