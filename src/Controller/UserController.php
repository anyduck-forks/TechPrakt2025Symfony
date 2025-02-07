<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/api/v1')]
class UserController extends AbstractController
{
    private static $TEST_USERS = [
        [
            'id'    => '1',
            'email' => 'ipz231_gdi1@student.ztu.edu.ua',
            'name'  => 'Victor', 
        ],
        [
            'id'    => '2',
            'email' => 'ipz231_gdi2@student.ztu.edu.ua',
            'name'  => 'Perto', 
        ],
        [
            'id'    => '3',
            'email' => 'ipz231_gdi3@student.ztu.edu.ua',
            'name'  => 'John', 
        ],
        [
            'id'    => '4',
            'email' => 'ipz231_gdi4@student.ztu.edu.ua',
            'name'  => 'Dan', 
        ],
        [
            'id'    => '5',
            'email' => 'ipz231_gdi5@student.ztu.edu.ua',
            'name'  => 'Brandon', 
        ],
    ];

    #[Route('/users', name: 'app_collection_users', methods: [Request::METHOD_GET])]
    #[IsGranted("ROLE_ADMIN")]
    public function get_collection(): JsonResponse
    {
        return new JsonResponse([
            'data' => self::$TEST_USERS,
        ], Response::HTTP_OK);
    }

    #[Route('/users/{id}', name: 'app_item_users', methods: [Request::METHOD_GET])]
    public function get_item(string $id): JsonResponse
    {
        return new JsonResponse([
            'data' => $this->find_user_by_id($id)
        ], Response::HTTP_OK);
    }


    #[Route('/users', name: 'app_create_users', methods: [Request::METHOD_POST])]
    public function create_item(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset($requestData['email'], $requestData['name'])) {
            throw new UnprocessableEntityHttpException("name and email are required");
        }
        if (!filter_var($requestData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new UnprocessableEntityHttpException("valid email is required");
        }

        $last_id = !empty(self::$TEST_USERS) ? end(self::$TEST_USERS)['id']: 0;

        $user = [
            'id'    => $last_id + 1,
            'name'  => $requestData['name'],
            'email' => $requestData['email'],
        ];

        // FIXME: symfony is stateless, so changes wont persist
        self::$TEST_USERS[] = $user;

        return new JsonResponse([
            'data' => $user
        ], Response::HTTP_CREATED);
    }

    #[Route('/users/{id}', name: 'app_delete_users', methods: [Request::METHOD_DELETE])]
    public function delete_item(string $id): JsonResponse
    {
        $initialCount = count(self::$TEST_USERS);
        // FIXME: symfony is stateless, so changes wont persist
        self::$TEST_USERS = array_filter(self::$TEST_USERS, fn($user) => $user['id'] !== $id);
    
        if (count(self::$TEST_USERS) >= $initialCount) {
            throw new NotFoundHttpException("User with id " . $id . " not found");
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }


    #[Route('/users/{id}', name: 'app_update_users', methods: [Request::METHOD_PATCH])]
    public function updateItem(string $id, Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset($requestData['name'])) {
            throw new UnprocessableEntityHttpException("name is required");
        }

        $userData = $this->find_user_by_id($id);

        // FIXME: symfony is stateless, so chagnes wont persist
        $userData['name'] = $requestData['name'];

        return new JsonResponse(['data' => $userData], Response::HTTP_OK);
    }

    public function find_user_by_id(string $id): array 
    {
        $user = current(array_filter(self::$TEST_USERS, fn($user) => $user['id'] === $id));
        if (!$user) {
            throw new NotFoundHttpException("User with id " . $id . " not found");
        }
        return $user;
    }
}
