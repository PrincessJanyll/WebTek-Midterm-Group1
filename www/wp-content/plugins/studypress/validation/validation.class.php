<?php

class validation{

    public $errors = array();

    private $validation_rules = array();

    public $sanitized = array();
    
    private $source = array();

    public function __construct()
    {
    }

    
    public function addSource($source, $trim=false)
    {
        $this->source = $source;
    }


    public function run()
    {
        foreach( new ArrayIterator($this->validation_rules) as $var=>$opt)
        {
            if($opt['required'] == true)
            {
                $this->is_set($var);
            }

            if( array_key_exists('trim', $opt) && $opt['trim'] == true )
            {
                $this->source[$var] = trim( $this->source[$var] );
            }

            switch($opt['type'])
            {
                case 'email':
                    $this->validateEmail($var, $opt['required']);
                    if(!array_key_exists($var, $this->errors))
                    {
                        $this->sanitizeEmail($var);
                    }
                    break;

                case 'url':
                    $this->validateUrl($var);
                    if(!array_key_exists($var, $this->errors))
                    {
                        $this->sanitizeUrl($var);
                    }
                    break;

                case 'numeric':
                    $this->validateNumeric($var, $opt['min'], $opt['max'], $opt['required']);
                    if(!array_key_exists($var, $this->errors))
                    {
                        $this->sanitizeNumeric($var);
                    }
                    break;

                case 'string':
                    $this->validateString($var, $opt['min'], $opt['max'], $opt['required']);
                    if(!array_key_exists($var, $this->errors))
                    {
                        $this->sanitizeString($var);
                    }
                break;

                case 'float':
                    $this->validateFloat($var, $opt['required']);
                    if(!array_key_exists($var, $this->errors))
                    {
                        $this->sanitizeFloat($var);
                    }
                    break;

                case 'ipv4':
                    $this->validateIpv4($var, $opt['required']);
                    if(!array_key_exists($var, $this->errors))
                    {
                        $this->sanitizeIpv4($var);
                    }
                    break;

                case 'ipv6':
                    $this->validateIpv6($var, $opt['required']);
                    if(!array_key_exists($var, $this->errors))
                    {
                        $this->sanitizeIpv6($var);
                    }
                    break;

                case 'bool':
                    $this->validateBool($var, $opt['required']);
                    if(!array_key_exists($var, $this->errors))
                    {
                        $this->sanitized[$var] = (bool) $this->source[$var];
                    }
                    break;
            }
        }
    }


    public function addRule($varname, $type, $required=false, $min=0, $max=0, $trim=false)
    {
        $this->validation_rules[$varname] = array('type'=>$type, 'required'=>$required, 'min'=>$min, 'max'=>$max, 'trim'=>$trim);
        /*** allow chaining ***/
        return $this;
    }


    public function AddRules(array $rules_array)
    {
        $this->validation_rules = array_merge($this->validation_rules, $rules_array);
    }

    private function is_set($var)
    {
        global $tr;
        if(!isset($this->source[$var]))
        {
            $this->errors[$var] = $var . $tr->__(' is not indicated');
        }
    }


    private function validateIpv4($var, $required=false)
    {
        global $tr;
        if($required==false && strlen($this->source[$var]) == 0)
        {
            return true;
        }
        if(filter_var($this->source[$var], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === FALSE)
        {
            $this->errors[$var] = $var . $tr->__(' is not a valid IPv4');
        }
    }

    
    public function validateIpv6($var, $required=false)
    {
        global $tr;
        if($required==false && strlen($this->source[$var]) == 0)
        {
            return true;
        }

        if(filter_var($this->source[$var], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === FALSE)
        {
            $this->errors[$var] = $var . $tr->__(' is not a valid IPv6');
        }
    }

    
    private function validateFloat($var, $required=false)
    {
        global $tr;
        if($required==false && strlen($this->source[$var]) == 0)
        {
            return true;
        }
        if(filter_var($this->source[$var], FILTER_VALIDATE_FLOAT) === false)
        {
            $this->errors[$var] = $var . $tr->__(' is an invalid float');
        }
    }

   
    private function validateString($var, $min=0, $max=0, $required=false)
    {
        global $tr;
        if($required==false && strlen($this->source[$var]) == 0)
        {
            return true;
        }

        if(isset($this->source[$var]))
        {
            if(strlen($this->source[$var]) < $min)
            {
                $this->errors[$var] = $var . $tr->__(' is too short');
            }
            elseif(strlen($this->source[$var]) > $max)
            {
                $this->errors[$var] = $var . $tr->__(' is too long');
            }
            elseif(!is_string($this->source[$var]))
            {
                $this->errors[$var] = $var . $tr->__(' is invalid');
            }
        }
    }

    
    private function validateNumeric($var, $min=0, $max=0, $required=false)
    {
        global $tr;
        if($required==false && strlen($this->source[$var]) == 0)
        {
            return true;
        }
        if(filter_var($this->source[$var], FILTER_VALIDATE_INT, array("options" => array("min_range"=>$min, "max_range"=>$max)))===FALSE)
        {
            $this->errors[$var] = $var . $tr->__(' is an invalid number');
        }
    }

    private function validateUrl($var, $required=false)
    {
        global$tr;
        if($required==false && strlen($this->source[$var]) == 0)
        {
            return true;
        }
        if(filter_var($this->source[$var], FILTER_VALIDATE_URL) === FALSE)
        {
            $this->errors[$var] = $var . $tr->__(' is an invalid URL');
        }
    }


    private function validateEmail($var, $required=false)
    {
        global $tr;
        if($required==false && strlen($this->source[$var]) == 0)
        {
            return true;
        }
        if(filter_var($this->source[$var], FILTER_VALIDATE_EMAIL) === FALSE)
        {
            $this->errors[$var] = $var . $tr->__(' is an invalid email address');
        }
    }


   
    private function validateBool($var, $required=false)
    {
        global $tr;
        if($required==false && strlen($this->source[$var]) == 0)
        {
            return true;
        }
        filter_var($this->source[$var], FILTER_VALIDATE_BOOLEAN);
        {
            $this->errors[$var] = $var . $tr->__(' is Invalid');
        }
    }

  
    private function sanitizeUrl($var)
    {
        $this->sanitized[$var] = (string) filter_var($this->source[$var],  FILTER_SANITIZE_URL);
    }

    
    private function sanitizeNumeric($var)
    {
        $this->sanitized[$var] = (int) filter_var($this->source[$var], FILTER_SANITIZE_NUMBER_INT);
    }


    private function sanitizeFloat($var)
    {
        $this->sanitized[$var] = (float) filter_var($this->source[$var], FILTER_SANITIZE_NUMBER_FLOAT);
    }

    
    private function sanitizeString($var)
    {
        $this->sanitized[$var] = (string) filter_var($this->source[$var], FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES );
    }


    public function getMessageErrors(){
        $msg = "";
        foreach ($this->errors as $error) {
            $msg .= $error . "<br/>" ;
        }

        return $msg;

    }

} 

