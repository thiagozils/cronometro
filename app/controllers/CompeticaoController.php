<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class CompeticaoController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for competicao
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Competicao', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";
      
        $competicao = Competicao::find($parameters);
        if (count($competicao) == 0) {
            $this->flash->notice("Não foi encontrado nenhum registro!");

            $this->dispatcher->forward([
                "controller" => "competicao",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $competicao,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {
        $competidores = Competidor::find();
        $this->view->competidores = $competidores;
    }

    /**
     * Edits a competicao
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $competicao = Competicao::findFirstByid($id);
            if (!$competicao) {
                $this->flash->error("Competição não econtrada");

                $this->dispatcher->forward([
                    'controller' => "competicao",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $competicao->id;
            $this->view->competicao = $competicao;

            $this->tag->setDefault("id", $competicao->id);
            $this->tag->setDefault("nome", $competicao->nome);
            $this->tag->setDefault("descricao", $competicao->descricao);
            $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $competicao->data);
            $newdate = $myDateTime->format('d/m/Y H:i:s');
            $this->tag->setDefault("data",$newdate );
            $this->tag->setDefault("tentativas",intval($competicao->tentativas));
            $this->tag->setDefault("tomadas", intval($competicao->tomadas));

            $competidores = Competidor::find();
            $this->view->competidores = $competidores;
        }
    }

    /**
     * Creates a new competicao
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "competicao",
                'action' => 'index'
            ]);

            return;
        }
    
        $competicao = new Competicao();
        $competicao->nome = $this->request->getPost("nome");
        $competicao->descricao = $this->request->getPost("descricao");
        $competicao->tomadas = $this->request->getPost("tomadas");
        $competicao->tentativas = $this->request->getPost("tentativas");
        $competicao->ativa = $this->request->getPost("ativa");
        $competidor = $this->request->getPost("competidores");
        $competidores = explode(",", $competidor);


        try {
            //CONVERTE A DATA PARA O FORMATO CORRETO
            $dateString = $this->request->getPost("data") . ":00";   
            $myDateTime = DateTime::createFromFormat('d/m/Y H:i:s', $dateString);
            $newdate = $myDateTime->format('Y/m/d H:i:s');
            $competicao->data = $newdate;
        }catch (Exception $e) {
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
           // $competicao->data = $this->request->getPost("data");
        }

        if (!$competicao->save()) {
            foreach ($competicao->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "competicao",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Competição criada com sucesso!");

        foreach ($competidores as &$value) {
            $compc = new Competicaocompetidor();
            $compc->id_competicao = $competicao->id;
            $compc->id_competidor = $value;
            $compc->save();
        }

        $this->dispatcher->forward([
            'controller' => "competicao",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a competicao edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "competicao",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $competicao = Competicao::findFirstByid($id);

        if (!$competicao) {
            $this->flash->error("Competição não existente " . $id);

            $this->dispatcher->forward([
                'controller' => "competicao",
                'action' => 'index'
            ]);

            return;
        }

        $competicao->nome = $this->request->getPost("nome");
        $competicao->descricao = $this->request->getPost("descricao");
        $competicao->ativa = $this->request->getPost("ativa");
        $competicao->tomadas = $this->request->getPost("tomadas");
        $competicao->tentativas = $this->request->getPost("tentativas");
        $competidor = $this->request->getPost("competidores");
        $competidores = explode(",", $competidor);

        try {
                //CONVERTE A DATA PARA O FORMATO CORRETO
                $dateString = $this->request->getPost("data") . ":00";   
                $myDateTime = DateTime::createFromFormat('d/m/Y H:i:s', $dateString);
                $newdate = $myDateTime->format('Y/m/d H:i:s');
                $competicao->data = $newdate;

                $competicao->competicaocompetidor->delete();
                foreach ($competidores as &$value) {
                    $compc = new Competicaocompetidor();
                    $compc->id_competicao = $competicao->id;
                    $compc->id_competidor = $value;
                    $compc->save();
                }
            } catch (Exception $e) {
                $this->flash->error($e->getMessage());
            }
                       
        if (!$competicao->save()) {

            foreach ($competicao->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "competicao",
                'action' => 'edit',
                'params' => [$competicao->id]
            ]);

            return;
        }
        




        $this->flash->success("Competição atualizada com sucesso!");

        $this->dispatcher->forward([
            'controller' => "competicao",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a competicao
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $competicao = Competicao::findFirstByid($id);
        $competicaocompetidor = Competicaocompetidor::find(['conditions' => 'id_competicao = '.$competicao->id] );
        $competicaocompetidor->delete();
        $volta = Volta::find(['conditions' => 'id_competicao = '.$competicao->id] );
        $volta->delete();


        if (!$competicao) {
            $this->flash->error("Competição não encontrada!");

            $this->dispatcher->forward([
                'controller' => "competicao",
                'action' => 'index'
            ]);

            return;
        }

        if (!$competicao->delete()) {

            foreach ($competicao->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "competicao",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Competição deletada com sucesso!");

        $this->dispatcher->forward([
            'controller' => "competicao",
            'action' => "index"
        ]);
    }


    public function detailsAction($id){

        if (!$this->request->isPost()) {

            $competicao = Competicao::findFirstByid($id);
            if (!$competicao) {
                $this->flash->error("Erro ao buscar");

                $this->dispatcher->forward([
                    'controller' => "competicao",
                    'action' => 'index'
                ]);

                return;
            }

            $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $competicao->data);
            $newdate = $myDateTime->format('d/m/Y H:i:s');
            $competicao->data = $newdate;

            $this->view->competicao = $competicao;

            $query = $this->modelsManager->createQuery('SELECT * FROM competidor WHERE competidor.id IN (SELECT competicaocompetidor.id_competidor FROM competicaocompetidor WHERE competicaocompetidor.id_competicao = '.$id.' )');
            $competidores  = $query->execute();
            $this->view->competidores = $competidores ;

        }

    }



    public function activateAction()
    {

        $this->view->disable();

        if($this->request->isPost() == true) 
        {
            if($this->request->isAjax() == true) 
            {              
                $id = $this->request->getPost("id");
                $competicao = Competicao::findFirstByid($id);
                $competicao->ativa = $this->request->getPost("ativa");
                
                //Para permitir uma competição ativa por vez
                $query = $this->modelsManager->createQuery('UPDATE competicao SET competicao.ativa = 0');
                $query->execute();
                
                if (!$competicao->save()) {
                    foreach ($competicao->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                }


            }
        }
        else
        {
            $this->response->setStatusCode(404, "Not Found");
        }
    }


    public function activeAction()
    {
        $competicao  = Competicao::findFirst(['conditions' => 'ativa = 1']);
        $this->response->setContentType('application/json', 'UTF-8');
        return $this->response
        ->setHeader('Content-Type', 'application/json')
        ->setJsonContent(array(['id' => $competicao->id,"tentativas" => $competicao->tentativas, "tomadas" => $competicao->tomadas]))
        ->send();
    }


    public function resultsAction($id){

        $competicao  = Competicao::findFirst(['conditions' => 'id = '.$id]);

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





