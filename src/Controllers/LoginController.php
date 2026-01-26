<?php

namespace EvanAlpst\ApiFoot\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use EvanAlpst\ApiFoot\Models\User;
use EvanAlpst\ApiFoot\Services\UserService;

class LoginController extends BaseController
{
    /**
     * Show login form
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function show(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->view->render($response, 'login/form.php', [
            'title' => 'Api Foot - Login',
            'withMenu' => false,
            'data' => [],
            'errors' => [],
        ]);
    }

    /**
     * Validate login data
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function validate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $data = $request->getParsedBody();

        $errors = [];

        $email = isset($data['email']) ? filter_var($data['email'], FILTER_VALIDATE_INT) : null;
        $password = isset($data['password']) ? filter_var($data['password'], FILTER_UNSAFE_RAW) : null;

        if ($email === null || $email === false) {
            $errors['email'] = "Le numero de compte est invalide";
        }

        if ($data['password'] === null || trim($data['password']) === '') {
            $errors['password'] = "Le mot de passe est obligatoire";
        }

        if (!empty($errors)) {
            return $this->view->render($response, "/login/form.php", [
                'title' => 'Api Foot - Login',
                'withMenu' => false,
                'data' => $data,
                'errors' => $errors,
            ]);
        }

        $user = User::findByEmail($email);

        if (!$user || !password_verify($password, $user->password)) {
            return $this->view->render($response, "/login/form.php", [
                'title' => 'Api Foot - Login',
                'withMenu' => false,
                'data' => $data,
                'errors' => [
                    'credentials' => 'Les identifiants fournis sont invalides',
                ]
            ]);
        }

        UserService::connect($user);

        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }
}
