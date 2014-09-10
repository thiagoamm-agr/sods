<?php
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
            return json_encode($this->toArray());
        }

        /*
         * Método que recebe um JSON (Javascript Object Notation)
         * e configura o estado do objeto.
         */
        public function loadJSON($json) {
            if (isset($json)) {
                // Converte de JSON para objeto stdClass.
                $obj = json_decode($json);
                // Obtém o nome da subclasse.
                $className = get_class($this);
                // Cria o objeto para reflexão.
                $class = new ReflectionClass($className);
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