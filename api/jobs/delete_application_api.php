<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $application_string = !empty($_GET['application_string']) ? (string)$_GET['application_string'] : "";
    if(!empty($application_string)){
        $application = JobApplications::find_by_application_string($application_string); 
        if(!empty($application)){
            $admin_string = !empty($_GET['admin_string']) ? (string)$_GET['admin_string'] : "";
            $job_string = !empty($_GET['job_string']) ? (string)$_GET['job_string'] : "";
            if(!empty($admin_string)){
                $admin = Admins::find_by_verify_string($admin_string);
                if(!empty($admin)){
                    if($application->delete()){
                        $return_array['status'] = 'success';
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = join(' ', $application->errors);
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No Admin was found';
                }
            } else if(!empty($job_string)){
                if($application->job_string == $job_string){
                    if($application->delete()){
                        $return_array['status'] = 'success';
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = join(' ', $application->errors);
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