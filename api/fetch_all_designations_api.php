<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $designations = array();
    $branches = Branches::find_all();
    if(!empty($branches)){
        foreach($branches as $branch){
            if(!in_array($branch->head_designation, $designations)){
                $designations[] = $branch->head_designation;
            }
        }
    }
    if(!empty($designations)){
        $data_array = array();
        sort($designations);
        foreach($designations as $designation){
            $data_array[] = array('designation' => $designation);
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No designation available';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>