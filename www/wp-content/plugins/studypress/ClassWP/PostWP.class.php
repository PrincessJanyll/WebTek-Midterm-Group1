<?php


class PostWP {


    public static function post(array $post)
    {
        return wp_insert_post($post, true);
    }



    public static function unPost($id)
    {
        wp_delete_post($id,true);
    }


    public static function updatePost(array $post)
    {
        wp_update_post($post);
    }



    public static function setPostPicture($postId,$imageId)
    {
        set_post_thumbnail($postId,$imageId);
    }



    public static function hasPostPicture($postId)
    {
        return has_post_thumbnail($postId);
    }



    public static function deletePostPicture($postId)
    {
        delete_post_thumbnail($postId);
    }


    public static function getChildrenPost($id){

        return get_children($id,ARRAY_A);
        
    }


} 