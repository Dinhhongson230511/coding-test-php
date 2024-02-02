<?php
declare(strict_types=1);

namespace App\Controller\Api;

use Cake\Http\Client\Message;
use App\Controller\AppController;
use Firebase\JWT\JWT;

/**
 * UsersController
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        // Add actions that should be unauthenticated
        $this->Authentication->addUnauthenticatedActions(['login']);
    }

    public function login() {
        $result = $this->Authentication->getResult();
    
        if ($result && $result->isValid()) {
            $user = $result->getData();

            $privateKey = file_get_contents(CONFIG . '/jwt.key');
            $payload = [
                'sub' => $user->id,
                'exp' => time()+600,
            ];

            $user = [
                'token' => JWT::encode($payload, $privateKey, 'RS256'),
                'userEntity' => $user
            ];
            return $this->setJsonResponse($user, Message::STATUS_OK, 'Successfully');
        }
        return $this->setJsonResponse([], 401, 'User not authenticated');
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
