<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();
    
    $post_json = @file_get_contents("php://input");
    if(!empty($post_json)){
        $data = json_decode($post_json);
        
        $comments = new Comments();
        $time = time();
        
        $comments->comment = !empty($data->comment) ? (string)$data->comment : "";
        $comments->blog_string = !empty($data->blog_string) ? (string)$data->blog_string : "";
        $comments->author_string = !empty($data->author_string) ? (string)$data->author_string : "";
        $comments->created_at = $time;
        $comments->updated_at = $time;

        if($comments->blog_string){
            if($comments->author_string){
                $user = Users::find_by_verify_string($comments->author_string);
                if(!empty($user)){
                    $comments->author = !empty($user->name) ? (string)$user->name : "";
                    if($comments->insert("create")){
                        $return_array['status'] = 'success';
                        $return_array['data'] = array(
                                'id' => $comments->id,
                                'comment_string' => $comments->comment_string,
                                'blog_string' => $comments->blog_string,
                                'author_string' => $comments->author_string,
                                'author' => $comments->author,
                                'comment' => $comments->comment,
                                'created_at' => $comments->created_at,
                                'updated_at' => $comments->updated_at
                            );
                    } else {
                        $return_array['status'] = 'failed';
                        $return_array['message'] = join(' ', $comments->errors);
                    }
                } else {
                    $return_array['status'] = 'failed';
                    $return_array['message'] = 'No User was found';
                }
            } else {
                $return_array['status'] = 'failed';
                $return_array['message'] = 'No means of Verification';
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