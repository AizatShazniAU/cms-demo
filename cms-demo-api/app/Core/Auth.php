<?php
namespace App\Core;
class Auth
{
    public function __construct(private JsonStore $store) {}
    public function attempt(string $email, string $password): ?array
    {
        foreach ($this->store->read('users') as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password_hash'])) {
                unset($user['password_hash']);
                return $user;
            }
        }
        return null;
    }
    public function issueToken(array $user): string
    {
        $tokens = $this->store->read('tokens');
        $token = bin2hex(random_bytes(32));
        $tokens[] = [
            'token' => hash('sha256', $token),
            'user_id' => $user['id'],
            'created_at' => gmdate('c'),
            'expires_at' => gmdate('c', time() + (int) env('TOKEN_TTL_MINUTES', 120) * 60),
        ];
        $this->store->write('tokens', $tokens);
        return $token;
    }
    public function userFromBearer(?string $bearerToken): ?array
    {
        if (! $bearerToken) { return null; }
        $hashed = hash('sha256', $bearerToken);
        $tokenRow = null;
        foreach ($this->store->read('tokens') as $token) {
            if (($token['token'] ?? '') === $hashed && strtotime($token['expires_at']) > time()) {
                $tokenRow = $token;
                break;
            }
        }
        if (! $tokenRow) { return null; }
        foreach ($this->store->read('users') as $user) {
            if ($user['id'] === $tokenRow['user_id']) {
                unset($user['password_hash']);
                return $user;
            }
        }
        return null;
    }
}
