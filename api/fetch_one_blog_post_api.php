<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();

    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        // $data = json_decode($post_json);

        $blogpost = BlogPost::find_by_blog_string($string);
        if(!empty($blogpost)){
            $return_array['status'] = 'success';
            $return_array['data'] = array(
                    'id' => $blogpost->id,
                    'blog_string' => $blogpost->blog_string,
                    'title' => $blogpost->title,
                    "snippet" => $blogpost->snippet,
                    'author' => $blogpost->author,
                    'priority' => $blogpost->priority,
                    'post' => $blogpost->post,
                    'file_path' => $blogpost->file_path,
                    'created_at' => $blogpost->created_at,
                    'updated_at' => $blogpost->updated_at
                );
        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Blog Post was found';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>