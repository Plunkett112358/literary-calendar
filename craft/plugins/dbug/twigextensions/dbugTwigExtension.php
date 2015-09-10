<?php

namespace Craft;

class DbugTwigExtension extends \Twig_Extension
{
    protected $env;

    public function getName()
    {
        return 'dbug';
    }

    public function getFilters()
    {
        return array(
            'dbug'  => new \Twig_Filter_Method($this, 'dbug', array('is_safe' => array('html')))
        );
    }
    public function getFunctions()
    {
        return array(
            'dbug'  => new \Twig_Function_Method($this, 'dbug', array('is_safe' => array('html')))
        );
    }

    public function dbug($var, $name = "DBUG", $isCollapsed=true, $forceType="")
    {

        $main_array = array();
        $html = "";

        if (is_null($var)) {
            $main_array["Null"] = Null;
            $html = $this->pretty($main_array,$forceType,$isCollapsed,$name);
        } elseif (is_numeric($var)){
            $main_array["Number"] = $var;
            $html = $this->pretty($main_array,$forceType,$isCollapsed,$name);
        } elseif (is_string($var)){
            $main_array["String"] = $var;
            $html = $this->pretty($main_array,$forceType,$isCollapsed,$name);
        } elseif (is_array($var)) {
            $main_array["Data"] = $var;
            $html = $this->pretty($main_array,$forceType,$isCollapsed,$name);
        } elseif (is_object($var)) {

            if (method_exists($var, 'getHelpText')) {
                $main_array["Help Info"] = $var->getHelpText();
            }

            $attributes = array();

            if (method_exists($var, 'getAttributes')) {
                $attributes = $var->getAttributes();

            }

            $reflector = new \ReflectionClass($var);
            foreach ($reflector->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
                $attributes[$property->name] = $property->getValue($var);
            }

            ksort($attributes);
            $main_array["Attrubutes"] = $attributes;

            $fields = $this->fieldsToArray($var);

            $main_array["Fields"] = $fields;


            $methods = array();

            foreach ($reflector->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if ('_' !== substr($method->name, 0, 1)) {
                    $methods[] = "\n    ".$method->name;
                }
            }

            if ($methods) {
                sort($methods);
                $main_array["Methods"] = $methods;
            }

            $html .= $this->pretty($main_array,$forceType,$isCollapsed,$name);

        }else {
            $html .= $this->pretty($var,$forceType,$isCollapsed,$name);
        }

        return $html;

    }

    public function fieldsToArray($var) {

      $elementType = craft()->elements->getElementTypeById($var->id);

      if ($elementType == 'Entry') {
        $fields = $var->getType()->getFieldLayout()->getFields();
      } else {
        $fields = $var->getFieldLayout()->getFields();
      }

      $fieldsArray = array();

      foreach ($fields as $field) {

        $field = $field->getField();
        $handle = $field->handle;
        $value = $var->$handle;
        //$fieldsArray[$handle] = $field->type;

        // one level down

        if (is_array($value)) {

          $fieldsArray[$handle] = $value;

        } else {

          $fieldsArray[$handle] = $field->type;
          
        }
      }

      return $fieldsArray;

    }

    public function pretty($var, $forceType="", $bCollapsed=true, $var_name='' )
    {
        $html = "";

        ob_start();
            new \Ospinto\Dbug($var, $forceType, $bCollapsed, $var_name);
            $html .= ob_get_contents();
        ob_end_clean();

        return $html;
    }
}
