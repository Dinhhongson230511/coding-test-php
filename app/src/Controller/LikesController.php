<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Http\Client\Message;

/**
 * Likes Controller
 *
 * @property \App\Model\Table\LikesTable $Likes
 * @method \App\Model\Entity\Like[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LikesController extends AppController
{

    public function like($articleId)
    {
        $userId = $this->Authentication->getIdentity()->get('id') ?? null;
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

    protected function setJsonResponse($data = [], $status = 200, $message)
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
