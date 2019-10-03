<?php
use Phalcon\Mvc\Model\Relation;
class Competicaocompetidor extends \Phalcon\Mvc\Model
{

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("cronometro");
        $this->setSource("competicaocompetidor");
        $this->hasManyToMany('id_competicao', 'Competicao', 'id', ['alias' => 'Competicao','foreignKey' => [
            'action' => Relation::ACTION_CASCADE]]);
        $this->hasManyToMany('id_competidor', 'Competidor', 'id', ['alias' => 'Competidor','foreignKey' => [
            'action' => Relation::ACTION_CASCADE]]);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'competicaocompetidor';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Competicaocompetidor[]|Competicaocompetidor|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Competicaocompetidor|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
