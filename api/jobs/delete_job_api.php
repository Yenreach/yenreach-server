<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $job_string = !empty($_GET['job_string']) ? (string)$_GET['job_string'] : "";
    if(!empty($job_string)){
        $job = Jobs::find_by_job_string($job_string); 
        if(!empty($job)){
            $admin_string = !empty($_GET['admin_string']) ? (string)$_GET['admin_string'] : "";
            $business_string = !empty($_GET['business_string']) ? (string)$_GET['business_string'] : "";
            if(!empty($admin_string)){
                $admin = Admins::find_by_verify_string($admin_string);
                if(!empty($admin)){
                    if($job->delete()){
                        $return_array['status'] = 'success';
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = join(' ', $job->errors);
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Admin was found';
                }
            } else if(!empty($business_string)){
                if($job->business_string == $business_string){
                    if($job->delete()){
                        $return_array['status'] = 'success';
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = join(' ', $job->errors);
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Business was found';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Admin or User was found';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Blog was fetched';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>