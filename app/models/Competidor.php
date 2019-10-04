<?php

use Phalcon\Mvc\Model\Relation;
class Competidor extends \Phalcon\Mvc\Model
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("cronometro");
        $this->setSource("competidor");
        $this->hasMany('id', 'CompeticaoCompetidor', 'id_competidor', ['alias' => 'CompeticaoCompetidor','foreignKey' => [
            'action' => Relation::ACTION_CASCADE]]);
        $this->hasMany('id', 'Volta', 'id_competidor', ['alias' => 'Volta','foreignKey' => [
            'action' => Relation::ACTION_CASCADE]]);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'competidor';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Competidor[]|Competidor|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Competidor|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
