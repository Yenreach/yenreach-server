<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $application = new JobApplications();
        $application->job_string = !empty($post->job_string) ? (string)$post->job_string : "";
        $application->user_string = !empty($post->user_string) ? (string)$post->user_string : "";
        $application->full_name = !empty($post->full_name) ? (string)$post->full_name : "";
        $application->email = !empty($post->email) ? (string)$post->email : "";
        $application->phone = !empty($post->phone) ? (string)$post->phone : "";
        $application->document = !empty($post->document) ? (string)$post->document : "";
        $application->status = "pending";
  
        $job = Jobs::find_by_job_string($application->job_string);
        if($job){
            if($application->insert()){
                $return_array['status'] = 'success';
                $return_array['data'] = array(
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
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = join(' ', $application->errors);
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'job does not exist';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>