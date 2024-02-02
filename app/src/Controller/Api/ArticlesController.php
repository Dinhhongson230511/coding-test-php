<?php
declare(strict_types=1);

namespace App\Controller\Api;

use Cake\ORM\TableRegistry;
use Cake\Http\Client\Message;
use App\Controller\AppController;

/**
 * Articles Controller
 *
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Likes');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $userAuth = $this->Authentication->getResult();
        $articlesTable = TableRegistry::getTableLocator()->get('Articles');
        $articleQuery = $articlesTable->find('withLikeCount');

        if ($userAuth && $userAuth->isValid()) {
            $userId = $this->Authentication->getIdentity()->get('id');
            $articleQuery->contain(['Likes' => function ($query) use ($userId) {
                return $query->where(['Likes.user_id' => $userId]);
            }]);
        }
        $articles = $articleQuery->all();

        return $this->setJsonResponse($articles, Message::STATUS_OK, 'Successfully');
    }

    /**
     * View method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $article = $this->Articles->find()->where(['id' => $id])->first();
        if($article) {
            return $this->setJsonResponse($article, Message::STATUS_OK, 'Successfully');
        }
        return $this->setJsonResponse([], 404, 'Not Found Article');
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $article = $this->Articles->newEmptyEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            if ($article = $this->Articles->save($article)) {
                return $this->setJsonResponse($article, Message::STATUS_CREATED, 'Created Article Successfully');
            }
            return $this->setJsonResponse([], 400, 'Failed created article.');
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $article = $this->Articles->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'put'])) {
            $request = $this->request->getData();
            $userId = $this->Authentication->getIdentity()->get('id');
            $request['user_id'] = $userId;
            $article = $this->Articles->patchEntity($article, $request);
            if ($article = $this->Articles->save($article)) {
                return $this->setJsonResponse($article, Message::STATUS_CREATED, 'Updated Article Successfully');
            }
        }
        return $this->setJsonResponse([], 400, __('The article could not be saved. Please, try again.'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $article = $this->Articles->get($id);
        if ($this->Articles->delete($article)) {
            return $this->setJsonResponse($article, Message::STATUS_CREATED, 'Deleted Article Successfully');
        }

        return $this->setJsonResponse([], 400, __('The article could not be deleted. Please, try again.'));
    }

    public function like($articleId)
    {
        $userId = $this->Authentication->getIdentity()->get('id');
        $existingLike = $this->Likes->find()
            ->where(['article_id' => $articleId, 'user_id' => $userId])
            ->first();
        if ($existingLike) {
            return $this->setJsonResponse([], Message::STATUS_NO_CONTENT, 'You already liked this article.');
        } else {

            $like = $this->Likes->newEmptyEntity();
            $like->user_id = $userId;
            $like->article_id = $articleId;
            
            if ($this->Likes->save($like)) {
                return $this->setJsonResponse([], Message::STATUS_CREATED, 'Article liked successfully.');
            } else {
                return $this->setJsonResponse([], 400, 'Failed to like the article.');
            }
        }
    }

    protected function setJsonResponse($data = null, $status = 200, $message = '')
    {
        $response = [
            'message' => $message,
            'data' => $data
        ];
        $this->response = $this->response->withType('application/json')
            ->withStatus($status)
            ->withStringBody(json_encode($response));

        return $this->response;
    }
}
