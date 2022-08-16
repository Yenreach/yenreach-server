<?php
    require_once("../../includes_yenreach/initialize.php");
    $return_array = array();

    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    if(!empty($string)){
        
        // $data = json_decode($post_json);

        $comments = Comments::find_by_blog_string($string);
        if(!empty($comments)){
            $data_array = array();
            foreach($comments as $comment){
                $data_array[] = array(
                    'id' => $comment->id,
                    'comment_string' => $comment->comment_string,
                    'blog_string' => $comment->blog_string,
                    'author_string' => $comment->author_string,
                    'author' => $comment->author,
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at,
                    'updated_at' => $comment->updated_at
                );
            }
            $return_array['status'] = 'success';
            $return_array['data'] = $data_array;

        } else {
            $return_array['status'] = 'failed';
            $return_array['message'] = 'No Blog comments was found';
        }
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No means of Identification';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>