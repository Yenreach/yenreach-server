<?php
    require_once("../../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $post = json_decode($post_json);
 
        $job_string = !empty($post->job_string) ? (string)$post->job_string : "";
        if(!empty($job_string)){
            $job = Jobs::find_by_job_string($job_string); 
            if(!empty($job)){
                $admin_string = !empty($post->admin_string) ? (string)$post->admin_string : "";
                $business_string = !empty($post->business_string) ? (string)$post->business_string : "";
                $job->company_name = !empty($post->company_name) ? (string)$post->company_name : "";
                $job->job_title = !empty($post->job_title) ? (string)$post->job_title : "";
                $job->job_type = !empty($post->job_type) ? (string)$post->job_type : "";
                $job->location = !empty($post->location) ? (string)$post->location : "";
                $job->salary = !empty($post->salary) ? (string)$post->salary : "";
                $job->job_overview = !empty($post->job_overview) ? (string)$post->job_overview : "";
                $job->job_benefit = !empty($post->job_benefit) ? (string)$post->job_benefit : "";
                $job_tags = !empty($post->job_tags) ? (array)$post->job_tags : array();

                if(!empty($admin_string)){
                    $admin = Admins::find_by_verify_string($admin_string);
                    if(!empty($admin)){                   
                        if($job->insert()){
                            $tags = JobTags::find_by_job_string($job->job_string);
                            if(!empty($tags)){
                                foreach($tags as $tag){
                                    $tag->delete();
                                }
                            }
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
                                'status' => $job->status,
                                'tags' => $tags,
                                'created_at' => $job->created_at,
                                'updated_at' => $job->updated_at
                            );
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
                        if($job->insert()){
                            $tags = JobTags::find_by_job_string($job->job_string);
                            if(!empty($tags)){
                                foreach($tags as $tag){
                                    $tag->delete();
                                }
                            }

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
                                'status' => $job->status,
                                'tags' => $tags,
                                'created_at' => $job->created_at,
                                'updated_at' => $job->updated_at
                            );
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
                    $return_array['message'] = 'No Admin or Business was found';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Job was fetched';
            }
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No means of Identification';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No data was provided';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>