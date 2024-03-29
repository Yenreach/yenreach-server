<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $data = json_decode($post_json);
        
        $blogpost = new BlogPost();
        $time = time();
        $blogpost->title = !empty($data->title) ? (string)$data->title : "";
        $blogpost->author = !empty($data->author) ? (string)$data->author : "";
        $blogpost->snippet = !empty($data->snippet) ? (string)$data->snippet : "";
        $blogpost->post = !empty($data->post) ? (string)$data->post : "";
        $blogpost->admin_string = !empty($data->admin_string) ? (string)$data->admin_string : "";
        $blogpost->file_path = !empty($data->file_path) ? (string)$data->file_path : "";
        $blogpost->created_at = $time; 
        $blogpost->updated_at = $time;

        if($blogpost->admin_string){
            $admin = Admins::find_by_verify_string($blogpost->admin_string);
            if(!empty($admin)){
                if($blogpost->insert()){
                    $return_array['status'] = 'success';
                    $return_array['data'] = array(
                            'id' => $blogpost->id,
                            'blog_string' => $blogpost->blog_string,
                            'title' => $blogpost->title,
                            'author' => $blogpost->author,
                            'snippet' => $blogpost->snippet,
                            'post' => $blogpost->post,
                            'file_path' => $blogpost->file_path,
                            'created_at' => $blogpost->created_at,
                            'updated_at' => $blogpost->updated_at
                        );
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = join(' ', $blogpost->errors);
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No Admin was found';
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