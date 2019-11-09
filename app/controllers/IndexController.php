<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class IndexController extends ControllerBase
{


    public function indexAction()
    {
        $competicao  = Competicao::findFirst(['conditions' => 'ativa = 1']);

        if (is_object($competicao)){
            $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $competicao->data);
            $newdate = $myDateTime->format('d/m/Y H:i:s');
            $competicao->data = $newdate;
            $this->view->competicao = $competicao ;
            $this->view->active = 1;
            $query = $this->modelsManager->createQuery('SELECT DISTINCT volta.id_competidor from volta where volta.id_competicao ='.$competicao->id . ' and volta.valida = 1 ORDER by volta.tempo asc');
            $cc  = $query->execute();
            $voltaFinal = [];
            foreach ($cc as $compc){
                $voltas = Volta::find(['conditions' => 'id_competidor = '.$compc->id_competidor.' and id_competicao = '.$competicao->id]);
                $voltaFinal[$compc->id_competidor] = $voltas;
            }

            if (count($voltaFinal) == 0){
                $this->view->voltas = 0;
            }else{
                $this->view->voltas = $voltaFinal;
            }
            
         }else{
            $this->view->active = 0;
         }


    }

}
