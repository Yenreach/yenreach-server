<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
        
        $job_string = !empty($post->job_string) ? (string)$post->job_string : "";
        $jobs = Jobs::find_by_job_string($job_string);
        if($jobs){
            $job_tags = !empty($post->job_tags) ? (array)$post->job_tags : array();
            foreach($job_tags as $job_tag){
                $job_tag_new = new JobTags();
                $job_tag_new->job_string = $job_string;
                $job_tag_new->tag = $job_tag->tag;
                $job_tag_new->insert();
            }
            $tags = JobTags::find_by_job_string($job_string);
            $return_array['status'] = 'success';
            $return_array['data'] = $tags;
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'Job does not exist';
        }   
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>