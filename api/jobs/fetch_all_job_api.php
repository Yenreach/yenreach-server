<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $jobs = Jobs::find_all();
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
                'salary' => $job->salary,
                'job_link' => $job->job_link,
                'admin_job' => $job->admin_job,
                'job_overview' => $job->job_overview,
                'job_benefit' => $job->job_benefit,
                'status' => $job->status,
                'expiry_date' => $job->expiry_date, // '2019-12-31 23:59:59
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
