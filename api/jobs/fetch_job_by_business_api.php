<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();

    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";

    
    $jobs = Jobs::find_by_business_string($string);
    if(!empty($jobs)){
        $data_array = array();
        foreach($jobs as $job){
            $tags = JobTags::find_by_job_string($job->job_string);
            
            $data_array[] = array(
                'id' => $job->id,
                'job_string' => $job->job_string,
                'business_string' => $job->business_string,
                'company_name' => $job->company_name,
                'job_title' => $job->job_title,
                'job_type' => $job->job_type,
                'location' => $job->location,
                'job_link' => $job->job_link,
                'admin_job' => $job->admin_job,
                'salary' => $job->salary,
                'job_overview' => $job->job_overview,
                'job_benefit' => $job->job_benefit,
                'status' => $job->status,
                "job_tags" => $tags,
                'created_at' => $job->created_at,
                'updated_at' => $job->updated_at
            );
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No job was fetched';
    }

    $result = json_encode($return_array);
    echo $result;
