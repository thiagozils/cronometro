<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class VoltaController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for volta
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Volta', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $volta = Volta::find($parameters);
        if (count($volta) == 0) {
            $this->flash->notice("The search did not find any volta");

            $this->dispatcher->forward([
                "controller" => "volta",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $volta,
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
     * Edits a volta
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $volta = Volta::findFirstByid($id);
            if (!$volta) {
                $this->flash->error("volta was not found");

                $this->dispatcher->forward([
                    'controller' => "volta",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $volta->id;

            $this->tag->setDefault("id", $volta->id);
            $this->tag->setDefault("id_competicao", $volta->id_competicao);
            $this->tag->setDefault("id_competidor", $volta->id_competidor);
            $this->tag->setDefault("valida", $volta->valida);
            $this->tag->setDefault("tempo", $volta->tempo);
            $this->tag->setDefault("data", $volta->data);
            
        }
    }

    /**
     * Creates a new volta
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "volta",
                'action' => 'index'
            ]);

            return;
        }

        $volta = new Volta();
        $volta->idCompeticao = $this->request->getPost("id_competicao");
        $volta->idCompetidor = $this->request->getPost("id_competidor");
        $volta->valida = $this->request->getPost("valida");
        $volta->tempo = $this->request->getPost("tempo");
        $volta->data = $this->request->getPost("data");
        

        if (!$volta->save()) {
            foreach ($volta->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "volta",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("volta was created successfully");

        $this->dispatcher->forward([
            'controller' => "volta",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a volta edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "volta",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $volta = Volta::findFirstByid($id);

        if (!$volta) {
            $this->flash->error("volta does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "volta",
                'action' => 'index'
            ]);

            return;
        }

        $volta->idCompeticao = $this->request->getPost("id_competicao");
        $volta->idCompetidor = $this->request->getPost("id_competidor");
        $volta->valida = $this->request->getPost("valida");
        $volta->tempo = $this->request->getPost("tempo");
        $volta->data = $this->request->getPost("data");
        

        if (!$volta->save()) {

            foreach ($volta->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "volta",
                'action' => 'edit',
                'params' => [$volta->id]
            ]);

            return;
        }

        $this->flash->success("volta was updated successfully");

        $this->dispatcher->forward([
            'controller' => "volta",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a volta
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $volta = Volta::findFirstByid($id);
        if (!$volta) {
            $this->flash->error("volta was not found");

            $this->dispatcher->forward([
                'controller' => "volta",
                'action' => 'index'
            ]);

            return;
        }

        if (!$volta->delete()) {

            foreach ($volta->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "volta",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("volta was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "volta",
            'action' => "index"
        ]);
    }

}
