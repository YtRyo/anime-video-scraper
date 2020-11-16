<?php

namespace Config;

class User
{
    public $token = 'API_TOKEN_HERE';

    public function getToken()
    {
        return $this->token;
    }

    public function setToken(string $token___)
    {
        return $this->token = $token___;
    }
}
