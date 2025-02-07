<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

#[Route('/api/v1')]
class UserController extends AbstractController
{
    private static $TEST_USERS = [
        [
            'id'    => '1',
            'email' => 'ipz231_gdi1@student.ztu.edu.ua',
        ],
        [
            'id'    => '2',
            'email' => 'ipz231_gdi2@student.ztu.edu.ua',
        ],
        [
            'id'    => '3',
            'email' => 'ipz231_gdi3@student.ztu.edu.ua',
        ],
        [
            'id'    => '4',
            'email' => 'ipz231_gdi4@student.ztu.edu.ua',
        ],
        [
            'id'    => '5',
            'email' => 'ipz231_gdi5@student.ztu.edu.ua',
        ],
    ];

    #[Route('/users', name: 'app_collection_users', methods: [Request::METHOD_GET])]
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

        if (!filter_var($requestData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new UnprocessableEntityHttpException("valid email is required");
        }

        $last_id = !empty(self::$TEST_USERS) ? end(self::$TEST_USERS)['id']: 0;

        $user = [
            'id'    => $last_id + 1,
            'email' => $requestData['email']
        ];

        // FIXME: symfony is stateless, so chagnes wont persist
        self::$TEST_USERS[] = $user;

        return new JsonResponse([
            'data' => $user
        ], Response::HTTP_CREATED);
    }

    #[Route('/users/{id}', name: 'app_delete_users', methods: [Request::METHOD_DELETE])]
    public function delete_item(string $id): JsonResponse
    {
        $initialCount = count(self::$TEST_USERS);
        // FIXME: symfony is stateless, so chagnes wont persist
        self::$TEST_USERS = array_filter(self::$TEST_USERS, fn($user) => $user['id'] !== $id);
    
        if (count(self::$TEST_USERS) >= $initialCount) {
            throw new NotFoundHttpException("User with id " . $id . " not found");
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
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
