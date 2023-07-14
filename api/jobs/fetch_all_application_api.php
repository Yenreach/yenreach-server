<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $applications = JobApplications::find_all();
    if(!empty($applications)){
        $data_array = array();
        foreach($applications as $application){     
            $data_array[] = array(
                'id' => $application->id,
                'application_string' => $application->application_string,
                'job_string' => $application->job_string,
                'user_string' => $application->user_string,
                'full_name' => $application->full_name,
                'email' => $application->email,
                'phone' => $application->phone,
                'document' => $application->document,
                'status' => $application->status,
                'created_at' => $application->created_at,
                'updated_at' => $application->updated_at
            );
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No application was fetched';
    }

    $result = json_encode($return_array);
    echo $result;