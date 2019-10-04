<?php

class Volta extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $id_competicao;

    /**
     *
     * @var integer
     */
    public $id_competidor;

    /**
     *
     * @var integer
     */
    public $valida;

    /**
     *
     * @var string
     */
    public $tempo;

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
        $this->setSource("volta");
        $this->belongsTo('id_competicao', 'Competicao', 'id', ['alias' => 'Competicao']);
        $this->belongsTo('id_competidor', 'Competidor', 'id', ['alias' => 'Competidor']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'volta';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Volta[]|Volta|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Volta|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
