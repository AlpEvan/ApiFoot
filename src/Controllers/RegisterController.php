<?php

namespace EvanAlpst\ApiFoot\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use EvanAlpst\ApiFoot\Models\User;
use EvanAlpst\ApiFoot\Services\UserService;

class RegisterController extends BaseController
{
    /**
     * Show register form
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
     * Validate register data
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
        $firstname = isset($data['firstname']) ? filter_var($data['firstname'], FILTER_SANITIZE_SPECIAL_CHARS) : null;
        $lastname = isset($data['lastname']) ? filter_var($data['lastname'], FILTER_SANITIZE_SPECIAL_CHARS) : null;

        if ($email === null || $email === false) {
            $errors['email'] = "L'email du compte est invalide";
        } else {
            $existingUser = User::findByEmail($email);
            if ($existingUser) {
                $errors['email'] = "Cet email est déjà utilisé";
            }
        }

        if ($password === null || trim($password) === '') {
            $errors['password'] = "Le mot de passe est obligatoire";
        } elseif (strlen($password) < 6) {
            $errors['password'] = "Le mot de passe doit contenir au moins 6 caractères";
        }

        if ($firstname === null || trim($firstname) === '') {
            $errors['firstname'] = "Le prénom est obligatoire";
        }

        if ($lastname === null || trim($lastname) === '') {
            $errors['lastname'] = "Le nom est obligatoire";
        }

        if (!empty($errors)) {
            return $this->view->render($response, "/register/form.php", [
                'title' => 'Api Foot - Register',
                'withMenu' => false,
                'data' => $data,
                'errors' => $errors,
            ]);
        }

        try {
            $user = new User();
            $user->email = $email;
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            $user->firstname = $firstname;
            $user->lastname = $lastname;

            if (!$user->save()) {
                throw new \Exception("Erreur lors de la creation du compte");
            }

            UserService::connect($user);

            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);

        } catch (\Exception $e) {
            return $this->view->render($response, "/register/form.php", [
                'title' => 'Api Foot - Register',
                'withMenu' => false,
                'data' => $data,
                'errors' => [
                    'credentials' => 'Une erreur est survenue lors de la creation du compte'
                ]
            ]);
        }
    }
}