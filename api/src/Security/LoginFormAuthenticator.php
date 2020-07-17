<?php

namespace App\Security;

use App\Model\User\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Class LoginFormAuthenticator
 * @package App\Security
 */
class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
  use TargetPathTrait;

  private UrlGeneratorInterface $urlGenerator;
  private CsrfTokenManagerInterface $csrfTokenManager;
  private UserPasswordEncoderInterface $passwordEncoder;
  private UserRepository $userRepo;

  public function __construct(
    UrlGeneratorInterface $urlGenerator,
    UserPasswordEncoderInterface $passwordEncoder,
    CsrfTokenManagerInterface $csrfTokenManager,
	UserRepository $userRepo
  ) {
    $this->urlGenerator = $urlGenerator;
	$this->passwordEncoder = $passwordEncoder;
	$this->csrfTokenManager = $csrfTokenManager;
	$this->userRepo = $userRepo;
  }

  public function supports(Request $request)
  {
    return 'app_login' === $request->attributes->get('_route')
      && $request->isMethod('POST');
  }

  public function getCredentials(Request $request)
  {
    $credentials = [
      'uuid' => $request->request->get('uuid'),
      'password' => $request->request->get('password'),
      'csrf_token' => $request->request->get('_csrf_token'),
    ];
    $request->getSession()->set(
      Security::LAST_USERNAME,
      $credentials['uuid']
    );

    return $credentials;
  }

  public function getUser($credentials, UserProviderInterface $userProvider)
  {
    dump('зашли');
    $token = new CsrfToken('authenticate', $credentials['csrf_token']);
    if (!$this->csrfTokenManager->isTokenValid($token)) {
      throw new InvalidCsrfTokenException();
    }
    $user = $this->userRepo->getByUUID($credentials['uuid']);


    if (!$user) {
      // fail authentication with a custom error
      throw new CustomUserMessageAuthenticationException('Неверный логин и/или пароль.');
    }

    return $user;
  }

  public function checkCredentials($credentials, UserInterface $user)
  {
    return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
  }

  public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
  {
    if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
      return new RedirectResponse($targetPath);
    }

    return new RedirectResponse($this->urlGenerator->generate('admin'));
  }

  protected function getLoginUrl()
  {
    return $this->urlGenerator->generate('app_login');
  }
}
