<?php


class StudyPressUserWP {


    private $_userWP;



    public function __construct($id=null)
    {
        $this->_userWP =($id === null)? wp_get_current_user(): get_user_by( 'id', $id );
        return $this;
    }


    public static function isLoggedIn(){
        return  is_user_logged_in() ;
    }


    public static function exist($id)
    {
        return  get_user_by( 'id', $id )?true:false;
    }

    public function id(){
        return $this->_userWP->ID;
    }

    public function firstName(){
        return $this->_userWP->user_firstname;
    }

    public function lastName(){
        return $this->_userWP->user_lastname ;
    }


    public function email(){
        return $this->_userWP->user_email ;
    }


    public function displayName(){
        return $this->_userWP->display_name;
    }

    public function isAdministrator(){
        return current_user_can('manage_options');
    }


    public function can($capability){
        return current_user_can($capability);
    }

    public static function getAvatar($idUser,$size){
        return get_avatar($idUser,$size);
    }


    public function IsInterfaceAdmin(){
        return is_admin();
    }


    public static function getUserLink($idUser){
       return  get_edit_user_link($idUser);
}

    public static function getUserPostsLink($idUser){
        return get_author_posts_url($idUser);
    }

    public static function getUserById($idUser){
        return get_user_by('id',$idUser);

    }

    public static function getIdByDisplayName($displayName){
        global $wpdb;

        if ( ! $user = $wpdb->get_row( $wpdb->prepare(
            "SELECT ID FROM $wpdb->users WHERE display_name = '%s'", $displayName
        ) ) )
            return false;

        return $user->ID;

    }


} 