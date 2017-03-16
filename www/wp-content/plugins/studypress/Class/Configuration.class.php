<?php



class Configuration {

    private $_access;


    private $_config= array();


    public function __construct()
    {

        $this->_access = new AccessData;
        $this->_config['version'] = '1.1';
        $this->_config['showRate'] = 'true';
        $this->_config['responsive'] = 'true';
        $this->_config['bp_shareActivity'] = 'true';
        $this->_config['share_socialNetwork'] = 'true';
        $this->_config['bp_shareResult'] = 'true';
        $this->_config['sizePlayer'] = 'medium';
        $this->_config['showTag'] = 'true';
        $this->_config['showGlossary'] = 'true';
        $this->Init();


    }

    
    public function getConfig()
    {
        return $this->_config;
    }



    public function Init()
    {
        $table = StudyPressDB::getTableNameConfiguration();

        foreach ($this->_config as  $name=>$value) {

            $result = $this->_access->getRow(
                "SELECT ".StudyPressDB::COL_VALUE_CONFIG." FROM " . $table." WHERE ".StudyPressDB::COL_NAME_CONFIG." = '". $name. "'");

            if ($result) {

            $this->_config[$name] = $result[StudyPressDB::COL_VALUE_CONFIG];

        } else {

                $a = array(

                    StudyPressDB::COL_NAME_CONFIG => $name,
                    StudyPressDB::COL_VALUE_CONFIG => $value,
                );

                $this->_access->insert($table,$a);
            }

        }


}
    public function InsertConfig(array $config) {

        $table=StudyPressDB::getTableNameConfiguration();
        $this->_access->query('DELETE FROM '.$table);


        foreach ($config as $name => $value) {


            $a = array(

                StudyPressDB::COL_NAME_CONFIG => $name,
                StudyPressDB::COL_VALUE_CONFIG => $value,
            );

            $this->_access->insert($table, $a);


        }

        $this->_config=$config;

    }


}

?>