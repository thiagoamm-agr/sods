<?php

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/util.php';

    // Protege o script de acesso direito.
    script_guard();

    class Model {

        // Atributos.
        protected $id = 0;

        // Construtor
        public function __construct() {

        }

        // Destrutor
        public function __destruct() {

        }

        // Getters
        public function __get($field) {
             return $this->$field;
        }

        // Setters
        public function __set($field, $value) {
            $this->$field = $value;
        }

        // Método que converte o objeto em array.
        public function toArray() {
            $argc = func_num_args();
            if ($argc == 1) {
                $obj = func_get_arg(0);
            } else {
                $obj = $this;
            }
            $cls = new ReflectionClass(get_class($obj));
            $properties = $cls->getProperties();
            $array = array();
            foreach ($properties as $property) {
                $property->setAccessible(true);
                if (is_object($property->getValue($obj))) {
                    $array[$property->getName()] = $this->toArray($property->getValue($obj));
                } else {
                    $array[$property->getName()] = $property->getValue($obj);
                }
            }
            return $array;
        }

        /* Método que retorna o objeto em formato
         * JSON (Javascript Object Notation).
         */
        public function toJSON() {
            return json_encode($this->toArray(), JSON_PRETTY_PRINT);
        }

        /*
         * Método que recebe um JSON (Javascript Object Notation)
         * e configura o estado do objeto.
         */
        public function loadJSON($json) {
            if (isset($json)) {
                // Converte de JSON para objeto stdClass.
                $obj = json_decode($json);
                // Obtém os atributos do objeto. 
                $args = get_object_vars($obj);
                // Configura o estado do objeto.
                foreach ($args as $key => $value) {
                    $this->$key = $value;
                }
            }
        }
    }
?>