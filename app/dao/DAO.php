<?php

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/util.php';

    // Protege o script de acesso direto.
    script_guard();

    /**
     * Interface ou contrato lógico que estabelece as operações ou serviços fundamentais 
     * que todo objeto DAO (Data Access Object) deve oferecer (interface pública) 
     * a seus clientes.
     * 
     * @author thiago
     *
     */
    interface DAO {

        /**
         * Insere um objeto no banco de dados.
         * 
         * @param object $obj objeto a ser inserido
         */
        public function insert($obj);

        /**
         * Atualiza os dados de um objeto no banco de dados.
         * 
         * @param object $obj objeto a ser atualizado
         */
        public function update($obj);

        /**
         * Salva (persiste) um objeto no banco de dados. 
         * Salvar implica na criação de um novo registro (insert)
         * ou na atualização (update) do mesmo.
         * 
         * @param object $obj objeto a ser persistido
         */
        public function save($obj);

        /**
         * Remove um registro da tabela com base no id informado.
         * 
         * @param int $id id do registro
         */
        public function delete($id);

        /**
         * Retorna um registro armazenado em uma tabela
         * tendo como critério de filtragem um de seus campos
         * e o valor desse campo.
         * 
         * @param string $field campo do registro
         * @param object $value valor do campo
         */
        public function get($field, $value);

        /**
         * Retorna todos os registros armazenados em uma tabela.
         */
        public function getAll();

        /**
         * Filtra os registros armazenados em uma tabela 
         * através de critérios informados.
         * 
         * @param string $criteria critérios de filtragem
         */
        public function filter($criteria);

        /**
         * Conta os registros armazenados em uma tabela.
         * Podem ser informados critérios para a realização da contagem.
         *
         * @param string $criteria critérios de filtragem
         */
        public function count($criteria);

        /**
         * Método que realiza a paginação de registros de uma tabela.
         * 
         * @param number $rows quantidade de registros a serem retornadas
         * @param number $start registro inicial da paginação
         * @param string $criteria critério de escolha ou filtragem de registros
         */
        public function paginate($rows=10, $start=0, $criteria='');
    }
?>