<?php

namespace App\Service;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\LinkedIn;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class UserService
 * @package App\Service
 */
class LinkedinService

{
    /**
     * @var LinkedIn
     */
    private $provider;
    /**
     * @var SessionInterface
     */
    private $session;


    /**
     * UserService constructor.
     * @param ParameterBagInterface $bag
     * @param RouterInterface $router
     * @param SessionInterface $session
     */
    public function __construct(ParameterBagInterface $bag, RouterInterface $router, SessionInterface $session)
    {
        $this->provider = new LinkedIn([
            'clientId' => $bag->get('linkedin_client_id'),
            'clientSecret' => $bag->get('linkedin_client_secret'),
            'redirectUri' => $router->generate('app_login_linkedin', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        $this->session = $session;
    }

    public function redirectToLinkedin(): Response
    {
        $url =$this->provider->getAuthorizationUrl();
        $this->session->set('oauth2state', $this->provider->getState());
        return new RedirectResponse($url);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isAuthenticated(Request $request): bool
    {
        $state = $request->query->get('state');

        $isAuthenticated = ($state !== null && $state === $this->session->get('oauth2state'));
        if ($isAuthenticated === false) {
            $this->session->remove('oauth2state');
        }

        return $isAuthenticated;
    }

    /**
     * @param string $code
     * @return ResourceOwnerInterface
     * @throws IdentityProviderException
     */
    public function getUser(string $code):ResourceOwnerInterface
    {
        /**
         * @var AccessToken $token
         */
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);

        return $this->provider->getResourceOwner($token);
    }

}