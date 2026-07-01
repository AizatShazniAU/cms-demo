<?php
namespace App\Controllers;
use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
class AuthController
{
    public function __construct(private Auth $auth) {}
    public function login(): void
    {
        $input = Request::json();
        $email = trim($input['email'] ?? '');
        $password = (string) ($input['password'] ?? '');
        if ($email === '' || $password === '') { Response::error('Email and password are required.', 422); return; }
        $user = $this->auth->attempt($email, $password);
        if (! $user) { Response::error('Invalid demo credentials.', 401); return; }
        Response::success(['token' => $this->auth->issueToken($user), 'user' => $user], 'Login successful.');
    }
    public function user(array $user): void { Response::success($user, 'Authenticated user retrieved.'); }
}
