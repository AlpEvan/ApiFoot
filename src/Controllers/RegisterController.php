<?php

namespace EvanAlpst\ApiFoot\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use EvanAlpst\ApiFoot\Models\User;
use EvanAlpst\ApiFoot\Services\UserService;

class RegisterController extends BaseController
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
        return $this->view->render($response, 'register/form.php', [
            'title' => 'Api Foot - Register',
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

        $email = isset($data['email']) ? filter_var($data['email'], FILTER_VALIDATE_EMAIL) : null;
        $password = isset($data['password']) ? filter_var($data['password'], FILTER_UNSAFE_RAW) : null;
        $firstName = isset($data['firstName']) ? filter_var($data['firstName'], FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $lastName = isset($data['lastName']) ? filter_var($data['lastName'], FILTER_SANITIZE_SPECIAL_CHARS) : null;

        if ($email === null || $email === false) {
            $errors['email'] = "L'email du compte est invalide";
        }

        if ($data['password'] === null || trim($data['password']) === '') {
            $errors['password'] = "Le mot de passe est obligatoire";
        }

        if ($data['firstName'] === null || trim($data['firstName']) === '') {
            $errors['firstName'] = "Le firstname est obligatoire";
        }

        if ($data['lastName'] === null || trim($data['lastName']) === '') {
            $errors['lastName'] = "Le lastname est obligatoire";
        }

        if (!empty($errors)) {
            return $this->view->render($response, "/register/form.php", [
                'title' => 'Api Foot - Register',
                'withMenu' => false,
                'data' => $data,
                'errors' => $errors,
            ]);
        }

        return $response
            ->withHeader('Location', '/login/form.php')
            ->withStatus(302);
    }
}
