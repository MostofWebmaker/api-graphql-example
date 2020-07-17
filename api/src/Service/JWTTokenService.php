<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\JWTValueObject;
use App\Model\Flusher;
use App\Model\User\Entity\User;
use App\Model\User\Repository\JwtRefreshTokenRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Proxies\__CG__\App\Model\User\Entity\JwtRefreshToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JWTTokenService
{
    /** @var JWTTokenManagerInterface */
    private JWTTokenManagerInterface $tokenManager;

    /** @var RefreshTokenManagerInterface */
    private RefreshTokenManagerInterface $refreshTokenManager;
    /**
     * @var JwtRefreshTokenRepository
     */
    private JwtRefreshTokenRepository $jwtRefreshTokenRepository;
    /**
     * @var Flusher
     */
    private Flusher $flusher;

    /** @var ValidatorInterface */
    private ValidatorInterface $validator;

    /** @var LoggerInterface $logger */
    private LoggerInterface $logger;

    /**
     * JWTTokenService constructor.
     * @param JWTTokenManagerInterface $tokenManager
     * @param RefreshTokenManagerInterface $refreshTokenManager
     * @param JwtRefreshTokenRepository $jwtRefreshTokenRepository
     * @param Flusher $flusher
     * @param ValidatorInterface $validator
     * @param LoggerInterface $logger
     */
    public function __construct(
        JWTTokenManagerInterface $tokenManager,
        RefreshTokenManagerInterface $refreshTokenManager,
        JwtRefreshTokenRepository $jwtRefreshTokenRepository,
        Flusher $flusher,
        ValidatorInterface $validator,
        LoggerInterface $logger
    ) {
        $this->tokenManager = $tokenManager;
        $this->refreshTokenManager = $refreshTokenManager;
        $this->jwtRefreshTokenRepository = $jwtRefreshTokenRepository;
        $this->flusher = $flusher;
        $this->validator = $validator;
        $this->logger = $logger;
    }

    /**
     * @param User $user
     * @param int $ttlRefresh
     * @return JWTValueObject
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createJWT(User $user, int $ttlRefresh = 1209600)
    {
        $accessToken = $this->tokenManager->create($user);

        $datetime = new \DateTime();
        $datetime->modify('+' . $ttlRefresh . ' seconds');

        /** @var  JwtRefreshToken $refreshToken */
        if ($refreshToken = $this->jwtRefreshTokenRepository->findOneBy(['username' => $user->getUsername()])) {
            $refreshToken->setRefreshToken();
            $refreshToken->setValid($datetime);
            $refreshToken->setDateUpdated(new \DateTimeImmutable());
        } else {
            $refreshToken = $this->refreshTokenManager->create();
            $refreshToken->setUsername($user->getUsername());
            $refreshToken->setDateCreated(new \DateTimeImmutable());
            $refreshToken->setRefreshToken();
            $refreshToken->setValid($datetime);
            $this->flusher->persist($refreshToken);
        }

        // Validate, that the new token is a unique refresh token
        $valid = false;
        while (false === $valid) {
            $valid = true;
            $errors = $this->validator->validate($refreshToken);
            if ($errors->count() > 0) {
                foreach ($errors as $error) {
                    if ('refreshToken' === $error->getPropertyPath()) {
                        $valid = false;
                        $refreshToken->setRefreshToken();
                    }
                }
            }
        }
        $this->flusher->flush();
        return new JWTValueObject($accessToken, $refreshToken->getRefreshToken());
    }

    /**
     * @param $data
     * @return string|string[]
     */
    public function base64UrlEncode($data)
    {
        return str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
    }
}