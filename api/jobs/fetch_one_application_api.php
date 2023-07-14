<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();

    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        // $data = json_decode($post_json);
        $application = JobApplications::find_by_application_string($string);
        if(!empty($application)){
            $data_array[] = array(
                'id' => $application->id,
                'application_string' => $application->application_string,
                'job_string' => $application->application_string,
                'user_string' => $application->user_string,
                'full_name' => $application->full_name,
                'email' => $application->email,
                'phone' => $application->phone,
                'document' => $application->document,
                'status' => $application->status,
                'created_at' => $application->created_at,
                'updated_at' => $application->updated_at
            );
            $return_array['status'] = 'success';
            $return_array['data'] = $data_array;
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Job Application was found';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>