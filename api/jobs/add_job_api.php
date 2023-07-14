<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $job = new Jobs();
        $job->business_string = !empty($post->business_string) ? (string)$post->business_string : "";
        $job->company_name = !empty($post->company_name) ? (string)$post->company_name : "";
        $job->job_title = !empty($post->job_title) ? (string)$post->job_title : "";
        $job->job_type = !empty($post->job_type) ? (string)$post->job_type : "";
        $job->location = !empty($post->location) ? (string)$post->location : "";
        $job->salary = !empty($post->salary) ? (string)$post->salary : "";
        $job->job_overview = !empty($post->job_overview) ? (string)$post->job_overview : "";
        $job->job_benefit = !empty($post->job_benefit) ? (string)$post->job_benefit : "";
        $job->expiry_date = !empty($post->expiry_date) ? (string)$post->expiry_date : "";
        $job->status = true;
        $job->admin_job = false;

        $job_tags = !empty($post->job_tags) ? (array)$post->job_tags : array();
        
        
        $business = Businesses::find_by_verify_string($job->business_string);
        if($business){
            if($job->insert()){
                foreach($job_tags as $job_tag){
                    $job_tag_new = new JobTags();
                    $job_tag_new->job_string = $job->job_string;
                    $job_tag_new->tag = $job_tag->tag;
                    $job_tag_new->insert();
                }

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
                        'job_tags' => $tags,
                        'status' => $job->status,
                        'job_link' => $job->job_link,
                        'admin_job' => $job->admin_job,
                        'expiry_date' => $job->expiry_date, // '2019-12-31 23:59:59
                        'created_at' => $job->created_at,
                        'updated_at' => $job->updated_at
                    );
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = join(' ', $job->errors);
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'Business does not exist';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>