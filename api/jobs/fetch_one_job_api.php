<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();

    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        // $data = json_decode($post_json);

        $job = Jobs::find_by_job_string($string);
        if(!empty($job)){
            $tags = JobTags::find_by_job_string($job->job_string);

            $return_array['status'] = 'success';
            $return_array['data'] = array(
                'id' => $job->id,
                'job_string' => $job->job_string,
                'business_string' => $job->business_string,
                'company_name' => $job->company_name,
                'job_title' => $job->job_title,
                'job_type' => $job->job_type,
                'location' => $job->location,
                'salary' => $job->salary,
                'job_overview' => $job->job_overview,
                'job_benefit' => $job->job_benefit,
                'status' => $job->status,
                'expiry_date' => $job->expiry_date, // '2019-12-31 23:59:59
                'job_link' => $job->job_link,
                'admin_job' => $job->admin_job,
                'created_at' => $job->created_at,
                'updated_at' => $job->updated_at
            );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Job was found';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>