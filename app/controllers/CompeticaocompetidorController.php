<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Cronometro\Models;

class CompeticaocompetidorController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for competicaocompetidor
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Competicaocompetidor', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id_competicao";

        $competicaocompetidor = Competicaocompetidor::find($parameters);
        if (count($competicaocompetidor) == 0) {
            $this->flash->notice("The search did not find any competicaocompetidor");

            $this->dispatcher->forward([
                "controller" => "competicaocompetidor",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $competicaocompetidor,
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

    }

    /**
     * Edits a competicaocompetidor
     *
     * @param string $id_competicao
     */
    public function editAction($id_competicao)
    {
        if (!$this->request->isPost()) {

            $competicaocompetidor = Competicaocompetidor::findFirstByid_competicao($id_competicao);
            if (!$competicaocompetidor) {
                $this->flash->error("competicaocompetidor was not found");

                $this->dispatcher->forward([
                    'controller' => "competicaocompetidor",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id_competicao = $competicaocompetidor->id_competicao;

            $this->tag->setDefault("id_competicao", $competicaocompetidor->id_competicao);
            $this->tag->setDefault("id_competidor", $competicaocompetidor->id_competidor);
            
        }
    }

    /**
     * Creates a new competicaocompetidor
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "competicaocompetidor",
                'action' => 'index'
            ]);

            return;
        }

        $competicaocompetidor = new Competicaocompetidor();
        $competidor = Competidor::findFirst($this->request->getPost("id_competidor"));
        $competicao = Competicao::findFirst($this->request->getPost("id_competicao"));
        $competicaocompetidor->id_competicao =  $competicao->id;
        $competicaocompetidor->id_competidor = $competidor->id;

        if (!$competicaocompetidor->save()) {
            foreach ($competicaocompetidor->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "competicaocompetidor",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("competicaocompetidor was created successfully");

        $this->dispatcher->forward([
            'controller' => "competicaocompetidor",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a competicaocompetidor edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "competicaocompetidor",
                'action' => 'index'
            ]);

            return;
        }

        $id_competicao = $this->request->getPost("id_competicao");
        $competicaocompetidor = Competicaocompetidor::findFirstByid_competicao($id_competicao);

        if (!$competicaocompetidor) {
            $this->flash->error("competicaocompetidor does not exist " . $id_competicao);

            $this->dispatcher->forward([
                'controller' => "competicaocompetidor",
                'action' => 'index'
            ]);

            return;
        }

        $competicaocompetidor->idCompeticao = $this->request->getPost("id_competicao");
        $competicaocompetidor->idCompetidor = $this->request->getPost("id_competidor");
        

        if (!$competicaocompetidor->save()) {

            foreach ($competicaocompetidor->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "competicaocompetidor",
                'action' => 'edit',
                'params' => [$competicaocompetidor->id_competicao]
            ]);

            return;
        }

        $this->flash->success("competicaocompetidor was updated successfully");

        $this->dispatcher->forward([
            'controller' => "competicaocompetidor",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a competicaocompetidor
     *
     * @param string $id_competicao
     */
    public function deleteAction($id_competicao)
    {
        $competicaocompetidor = Competicaocompetidor::findFirstByid_competicao($id_competicao);
        if (!$competicaocompetidor) {
            $this->flash->error("competicaocompetidor was not found");

            $this->dispatcher->forward([
                'controller' => "competicaocompetidor",
                'action' => 'index'
            ]);

            return;
        }

        if (!$competicaocompetidor->delete()) {

            foreach ($competicaocompetidor->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "competicaocompetidor",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("competicaocompetidor was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "competicaocompetidor",
            'action' => "index"
        ]);
    }

}
