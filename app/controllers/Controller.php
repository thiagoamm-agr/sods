<?php

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/util.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/paginator.php';

    abstract class Controller {

        protected $dao;
        protected $paginator; 

        public function __construct() {
            $this->paginator = new Paginator();
        }

        public function __destruct() {
            unset($this->paginator);
        }

        // Getters
        public function __get($field) {
            return $this->$field;
        }

        // Setters
        public function __set($field, $value) {
            $this->$field = $value;
        }

        abstract public function _list();

        abstract public function add($model);

        abstract public function edit($model);

        abstract public function delete($id);

        abstract public function paginate();
    }
?>