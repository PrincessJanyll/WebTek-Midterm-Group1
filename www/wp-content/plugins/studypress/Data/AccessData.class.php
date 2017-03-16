<?php


class AccessData {

    public function __construct(){
        
    }

    

    public function insert($table,array $columns){

        global $wpdb;
        $wpdb->insert(
                      $table ,
                      $columns
                     );
    }


    
    public function update($table,array $updatedColumns,array $condition){

        global $wpdb;
        $wpdb->update(
            $table, 
            $updatedColumns,
            $condition 
        );
    }

    
    public function delete($table,array $condition){
        global $wpdb;
        $wpdb->delete(
            $table, 
            $condition 
        );

    }


    public function query($sql){

        global $wpdb;
        return $wpdb->query($sql);
    }
    
    

    public function getLastError(){
        global $wpdb;
        return $wpdb->last_error;
    }


    public function setMsgError($m){
        global $wpdb;
        $wpdb->last_error = $m;
    }

    
    
    public function getResults($sql){

        global $wpdb;
        return $wpdb->get_results($sql,'ARRAY_A');
    }
    

    public function prepare($sql,$args){
        global $wpdb;
        return $wpdb->prepare($sql,$args);
    }

    public function getRow($sql){

        global $wpdb;
        return $wpdb->get_row($sql,'ARRAY_A');

    }

    
    public function getVar($sql, $column_offset=0, $row_offset=0){
        global $wpdb;
        return $wpdb->get_var($sql, $column_offset, $row_offset);
    }


    public function getLastInsertId(){
        global $wpdb;
        return $wpdb->insert_id;
    }
} 