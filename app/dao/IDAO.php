<?php
	interface IDAO {
		public function insert($obj);
		public function update($obj);
		public function save($obj);
		public function delete($id);
 		public function get($field, $value);
		public function getAll();
	}
?>