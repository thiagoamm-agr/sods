<?php
	class Model {
		// Atributos.
		protected $id;

		public function __construct() {
			$this->id = 0;
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
		function toArray() {
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
		function toJSON() {
			return json_encode($this->toArray());
		}
	}
?>