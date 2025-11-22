<?php

namespace Controller;

use Service\UserService;
use Http\Request;
use Http\Response;
use Error\APIException;

class UserController
{
    private UserService $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    public function processRequest(Request $request)
    {
        $method = $request->getMethod();
        $id = $request->getId(); // Pode ser o ID numérico ou "me"
        $body = $request->getBody();

        switch ($method) {
            case 'POST':
                // Rota de cadastro (Sign-up)
                $novoUser = $this->service->cadastrar($body);
                Response::send($novoUser, 201);
                break;

            case 'GET':
                if ($id) {
                    $user = $this->service->buscarPorId($id);
                    Response::send($user);
                } else {
                    throw new APIException("Listagem de todos os usuários desabilitada por segurança.", 403);
                }
                break;
            
            default:
                throw new APIException("Método não suportado para Usuários.", 405);
        }
    }
}