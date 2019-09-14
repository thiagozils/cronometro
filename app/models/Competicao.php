<?php

class Competicao extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $nome;

    /**
     *
     * @var string
     */
    public $descricao;

    /**
     *
     * @var string
     */
    public $data;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("cronometro");
        $this->setSource("competicao");
        $this->hasMany('id', 'CompeticaoCompetidor', 'id_competicao', ['alias' => 'CompeticaoCompetidor']);
        $this->hasMany('id', 'Volta', 'id_competicao', ['alias' => 'Volta']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'competicao';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Competicao[]|Competicao|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Competicao|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
