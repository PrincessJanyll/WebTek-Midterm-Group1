<?php

if(isset($_POST['type']) && ($_POST['type'] === "update-ajax"))
{
    if(isset($_POST['name']) && isset($_POST['desc']) && isset($_POST['id']))
    {
        require_once '_AutoLoadClassAjax.php';

        global $tr;

        $v = new validation();
        $v->addSource($_POST);
        $v->AddRules(array(

            'name' => array(
                'type' => 'string',
                'required' => 'true',
                'min' => '1',
                'max' => '200',
                'trim' => 'true'
            ),


            'id' => array(
                'type' => 'numeric',
                'required' => 'true',
                'min' => '1',
                'max' => '999999',
                'trim' => 'true'
            )
        ));

        $v->run();


        if ((sizeof($v->errors)) > 0) {
            header("HTTP/1.0 400 Bad Request");
            echo $v->getMessageErrors();
        } else {
            $managerDomain = new DomainManager();
            $managerDomain->update($v->sanitized['id'],new Domain(array(
                    'name' => $v->sanitized['name'],
                    'description' => $_POST['desc']
                )
            ));
            if($managerDomain->isError())
            {
                header("HTTP/1.0 400 Bad Request");
                echo $tr->__("This name already exist")." !!";
            }
            else
            {
                echo "true";
            }
        }
    }

}


else
{

    if ( !defined( 'ABSPATH' ) ) exit;


    global $tr;


    $managerDomain = new DomainManager();


    $error_domain_add = "";


    $error_domain_remove = "";


    if (isset($_POST)) {
    

        $validation = new validation();
    }
   
    if (isset($_POST['add'])) {

        $validation->addSource($_POST);
        
        $validation->AddRules(array(
                'name' => array(
                    'type' => 'string',
                    "required" => true,
                    'min' => '1',
                    'max' => '200',
                    'trim' => true
                ),
                'desc' => array(
                    'type' => 'string',
                    "required" => true,
                    'min' => '0',
                    'max' => '999999',
                    'trim' => true
                ))
        );
       
        $validation->run();

        

        if ((sizeof($validation->errors)) > 0)
            $error_domain_add = $validation->getMessageErrors();
        else {
            $managerDomain->add(new
            Domain(array(
                    'name' => $validation->sanitized['name'],
                    'description' => $validation->sanitized['desc']
                )));

            if ($managerDomain->isError()) {
                $error_domain_add = $tr->__("This name already exist");
            }

        }


    }

    
    if (isset($_POST['remove'])) {
        if ((isset($_POST['id'])) && (!empty($_POST['id']))) {
            $rules = array();
           
            $validation->addSource($_POST['id']);
            for ($i = 0; $i < count($_POST['id']); ++$i) {


                $rules[] = array(
                    'type' => 'numeric', "required" => true, 'min' => '0', 'max' => '10000', 'trim' => true
                );

            }

            $validation->AddRules($rules);


            $validation->run();
            //var_dump($validation);
            if ((sizeof($validation->errors)) > 0)
                $error_cat_remove = $validation->getMessageErrors();
            else {
                foreach ($validation->sanitized as $id) {
                    $managerDomain->delete($id);

                    if ($managerDomain->isError()) {
                        $error_domain_remove = $managerDomain->getMessageError();
                        break;
                    }

                }

            }
            
        } else {
            $error_domain_remove = $tr->__("Please select field(s) to delete");
        }

    }

    require_once __ROOT_PLUGIN__ . "Views/admin/domain.view.php";
}