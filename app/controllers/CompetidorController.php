<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class CompetidorController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for competidor
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Competidor', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $competidor = Competidor::find($parameters);
        if (count($competidor) == 0) {
            $this->flash->notice("The search did not find any competidor");

            $this->dispatcher->forward([
                "controller" => "competidor",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $competidor,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }


    public function searchJsonAction()
    {
        $competidor = Competidor::find();
        $this->response->setContentType('application/json', 'UTF-8');
        return $this->response->setJsonContent($competidor->toArray())->send();
        //echo json_encode(Competidor::find($parameters)->toArray(), JSON_NUMERIC_CHECK);
    }






    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a competidor
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $competidor = Competidor::findFirstByid($id);
            if (!$competidor) {
                $this->flash->error("competidor was not found");

                $this->dispatcher->forward([
                    'controller' => "competidor",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $competidor->id;

            $this->tag->setDefault("id", $competidor->id);
            $this->tag->setDefault("nome", $competidor->nome);
            
        }
    }

    /**
     * Creates a new competidor
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "competidor",
                'action' => 'index'
            ]);

            return;
        }

        $competidor = new Competidor();
        $competidor->nome = $this->request->getPost("nome");
        

        if (!$competidor->save()) {
            foreach ($competidor->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "competidor",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("competidor was created successfully");

        $this->dispatcher->forward([
            'controller' => "competidor",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a competidor edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "competidor",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $competidor = Competidor::findFirstByid($id);

        if (!$competidor) {
            $this->flash->error("competidor does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "competidor",
                'action' => 'index'
            ]);

            return;
        }

        $competidor->nome = $this->request->getPost("nome");
        

        if (!$competidor->save()) {

            foreach ($competidor->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "competidor",
                'action' => 'edit',
                'params' => [$competidor->id]
            ]);

            return;
        }

        $this->flash->success("competidor was updated successfully");

        $this->dispatcher->forward([
            'controller' => "competidor",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a competidor
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $competidor = Competidor::findFirstByid($id);
        if (!$competidor) {
            $this->flash->error("competidor was not found");

            $this->dispatcher->forward([
                'controller' => "competidor",
                'action' => 'index'
            ]);

            return;
        }

        if (!$competidor->delete()) {

            foreach ($competidor->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "competidor",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("competidor was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "competidor",
            'action' => "index"
        ]);
    }

}
