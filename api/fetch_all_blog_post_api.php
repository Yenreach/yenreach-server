<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $blogposts = BlogPost::find_all();
    if(!empty($blogposts)){
        $data_array = array();
        foreach($blogposts as $blogpost){
            $data_array[] = array(
                    "id" => $blogpost->id,
                    "blog_string" => $blogpost->blog_string,
                    "title" => $blogpost->title,
                    "snippet" => $blogpost->snippet,
                    "author" => $blogpost->author,
                    "post" => $blogpost->post,
                    "priority" => $blogpost->priority,
                    'file_path' => $blogpost->file_path,
                    "created_at" => $blogpost->created_at,
                    "updated_at" => $blogpost->updated_at
                );
        }
        $return_array['status'] = 'success';
        $return_array['data'] = $data_array;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Blog Post was fetched';
    }

    $result = json_encode($return_array);
    echo $result;
?>