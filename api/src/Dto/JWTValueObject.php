<?php
namespace App\Dto;

class JWTValueObject {
    /**
     * @var string
     */
    private string $accessToken;
    /**
     * @var string
     */
    private string $refreshToken;

    /**
     * JWTValueObject constructor.
     * @param string $accessToken
     * @param string $refreshToken
     */
    public function __construct(string $accessToken, string $refreshToken)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
