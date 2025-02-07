<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController
{
    #[Route('/users', name: 'app_collection_users', methods: [Request::METHOD_GET])]
    public function get_collection(): JsonResponse
    {
        return new JsonResponse([
            'data' => [
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
            ],
        ], Response::HTTP_OK);
    }
}
