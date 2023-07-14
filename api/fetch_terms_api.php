<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $terms = Terms::find_all();
    if(!empty($terms)){
        $data_array = array();
        foreach($terms as $term){
            $data_array[] = array(
                'id' => $term->id,
                'content' => $term->content,
                'created_at' => $term->created_at,
                'updated_at' => $term->updated_at,
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