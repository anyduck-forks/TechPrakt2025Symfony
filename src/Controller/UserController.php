<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController
{
    private const TEST_USERS = [
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
            'data' => self::TEST_USERS,
        ], Response::HTTP_OK);
    }

    #[Route('/users/{id}', name: 'app_item_users', methods: [Request::METHOD_GET])]
    public function get_item(string $id): JsonResponse
    {
        $user = current(array_filter(self::TEST_USERS, fn($user) => $user['id'] === $id));

        return new JsonResponse([
            'data' => $user
        ], Response::HTTP_OK);
    }
}
