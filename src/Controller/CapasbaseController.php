<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Capasbase Controller
 *
 * @property \App\Model\Table\CapasbaseTable $Capasbase
 */
class CapasbaseController extends AppController
{

    public function beforeFilter(\Cake\Event\Event $event)
    {
        // allow all action
        $this->Auth->allow('getCapasBase');
    }


    public function isAuthorized($user)
    {
        if(isset($user['role']) and $user['role'] === 'Admin')
        {
            if(in_array($this->request->action, ['index', 'add', 'edit']))
            {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }



    public function index()
    {
        //Recupero los datos de la URL
        $data_url = $this->request->query;

        //Obtengo los datos de la tabla

        $action = $data_url['Accion'];
        $categoria = $data_url['Categoria'];

        $capasbase = $this->Capasbase->find();

        $this->set(compact('capasbase'));
        $this->set('_serialize', ['capasbase']);

        $this->set('action', $action);
        $this->set('categoria', $categoria);

    }

    public function add()
    {
        //Recupero los datos de la URL
        $data_url = $this->request->query;

        //Obtengo los datos de la tabla
        $action = $data_url['Accion'];
        $categoria = $data_url['Categoria'];
        $this->set('action', $action);
        $this->set('categoria', $categoria);


        $capasbase = $this->Capasbase->newEntity();
        if ($this->request->is('post')) {
            $capasbase = $this->Capasbase->patchEntity($capasbase, $this->request->data);
            if ($this->Capasbase->save($capasbase)) {
                $this->Flash->success(__('La Capa Base se ha guardado correctamente!'));

                return $this->redirect(['action' => 'index', '?' => ['Accion' => 'Ver Capas Base', 'Categoria' => 'CapasBase']]);
            }
            $this->Flash->error(__('Error al guardar la Capa Base. Intente nuevamente.'));
        }
        $this->set(compact('capasbase'));
        $this->set('_serialize', ['capasbase']);


    }

    public function edit()
    {
        //Recupero los datos de la URL
        $data_url = $this->request->query;

        //Obtengo los datos de la tabla
        $action = $data_url['Accion'];
        $categoria = $data_url['Categoria'];
        $id = $data_url['id'];

        $capasbase = $this->Capasbase->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $capasbase = $this->Capasbase->patchEntity($capasbase, $this->request->data);
            if ($this->Capasbase->save($capasbase)) {

                $this->Flash->success(__('La Capa Base se ha guardado correctamente!'));
                return $this->redirect(['action' => 'index', '?' => ['Accion' => 'Ver Capas Base', 'Categoria' => 'CapasBase']]);
            } else {
                $this->Flash->error(__('Error al guardar la Capa Base. Intente nuevamente.'));
            }

        }
        $this->set(compact('capasbase'));
        $this->set('_serialize', ['capasbase']);


        $this->set('action', $action);
        $this->set('categoria', $categoria);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $capasbase = $this->Capasbase->get($id);


        try{
            if ($this->Capasbase->delete($capasbase)) {
                $this->Flash->success(__('La Capa Base se ha eliminado correctamente!.'));
                return $this->redirect(['action' => 'index', '?' => ['Accion' => 'Ver Capas Base', 'Categoria' => 'CapasBase']]);
            } else {
                $this->Flash->error(__('La Emsefor no pudo ser eliminada. Intente nuevamente.'));
            }
        }catch(\PDOException $e){
            $this->Flash->error(__($e->getMessage()));
            $this->Flash->error(__('La Capa Base no pudo ser eliminada. Intente nuevamente.'));
            return $this->redirect(['action' => 'index', '?' => ['Accion' => 'Ver Capas Base', 'Categoria' => 'CapasBase']]);
        }

    }


    /*
     * Metodo consultado por la post para obtener los datos iniciales del configuracion
     */
    public function getCapasBase()
    {
        $this->layout = null;
        $this->autoRender = false;

        if ($this->request->is('ajax')) {

            //Ingreso a la base de datos y traigo los parametro de configuracion

            $capasbase = $this->Capasbase->find('all', [
                'conditions' => ['active' => true]]);


            $res = [
                'capasbase' => $capasbase
            ];

            return $this->json($res);

        }

    }


}
