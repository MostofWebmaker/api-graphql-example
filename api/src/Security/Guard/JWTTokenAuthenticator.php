<?php

namespace App\Security\Guard;

use App\Model\User\Entity\UserStatusType;
use App\Model\User\Repository\UserRepository;
use App\Service\JWTTokenService;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator as BaseAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTTokenAuthenticator extends BaseAuthenticator
{
    /**
     * @var JWTTokenService
     */
    private JWTTokenService $jwtTokenService;
    /**
     * @var Sha256
     */
    private Sha256 $algorithm;

    private UserRepository $userRepository;

    /** @var LoggerInterface $logger */
    private LoggerInterface $logger;

    /**
     * JWTTokenAuthenticator constructor.
     * @param JWTTokenManagerInterface $jwtManager
     * @param EventDispatcherInterface $dispatcher
     * @param TokenExtractorInterface $tokenExtractor
     * @param JWTTokenService $jwtTokenService
     * @param Sha256 $algorithm
     * @param UserRepository $userRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        EventDispatcherInterface $dispatcher,
        TokenExtractorInterface $tokenExtractor,
        JWTTokenService $jwtTokenService,
        Sha256 $algorithm,
        UserRepository $userRepository,
        LoggerInterface $logger
    ) {
        parent::__construct($jwtManager, $dispatcher, $tokenExtractor);
        $this->jwtTokenService = $jwtTokenService;
        $this->algorithm = $algorithm;
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        return false !== $this->getTokenExtractor()->extract($request);
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $curUser =  $this->userRepository->getByUUID($user->getUsername());
        $statusId = $curUser->getUserStatus()->getUserStatusType() ? $curUser->getUserStatus()->getUserStatusType()->getId() : null;
        if (!$statusId) {
            throw new \RuntimeException("User with id #{$user->getUsername()} has not status id!");
        }
        if ($statusId === UserStatusType::STATUS_BANNED || $statusId === UserStatusType::STATUS_WAIT) {
            throw new AccessDeniedException('This action needs a valid token!');
        }

        if (!$pk = openssl_pkey_get_private(file_get_contents($_ENV['HOME'].'/'.$_ENV['JWT_SECRET_KEY']), $_ENV['JWT_PASSPHRASE'])) {
            $this->logger->error("Failed to get private key");
            throw new \RuntimeException('Failed to get private key');
        }
        $header = [
            'typ' => 'JWT',
            'alg' => $this->algorithm->getAlgorithmId()
        ];
        if (!$token = $this->jwtTokenService->base64UrlEncode(json_encode($header)).'.'.$this->jwtTokenService->base64UrlEncode(json_encode($credentials->getPayload()))) {
            $this->logger->error("Could not construct token of user #{$user->getUsername()} is invalid!");
            throw new \RuntimeException('Could not construct token!');
        }
        openssl_sign($token, $signature, $pk, $this->algorithm->getAlgorithm());
        $authToken = $token.'.'.$this->jwtTokenService->base64UrlEncode($signature);
        if ($credentials->getCredentials() !== $authToken) {
            $this->logger->error("This authentication token {$credentials->getCredentials()} of user #{$user->getUsername()} is invalid!");
            throw new \RuntimeException("This authentication token is invalid!");
        }    
        return true;
    }
}
