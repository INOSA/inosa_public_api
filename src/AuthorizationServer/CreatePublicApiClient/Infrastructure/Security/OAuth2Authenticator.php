<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\Infrastructure\Security;

use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use LogicException;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Trikoder\Bundle\OAuth2Bundle\Security\Authentication\Token\OAuth2Token;
use Trikoder\Bundle\OAuth2Bundle\Security\Authentication\Token\OAuth2TokenFactory;
use Trikoder\Bundle\OAuth2Bundle\Security\Exception\InsufficientScopesException;

final class OAuth2Authenticator extends AbstractAuthenticator
{
    private ServerRequestInterface $psr7Request;

    public function __construct(
        private HttpMessageFactoryInterface $httpMessageFactory,
        private ResourceServer $resourceServer,
        private OAuth2TokenFactory $oauth2TokenFactory,
        private RequestStack $requestStack,
    ) {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            throw new LogicException('No request');
        }

        $this->psr7Request = $this->httpMessageFactory->createRequest($request);
    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        $exception = new UnauthorizedHttpException('Bearer');

        return new Response('', $exception->getStatusCode(), $exception->getHeaders());
    }

    public function supports(Request $request): bool
    {
        $authorization = $request->headers->get('Authorization', '');

        if (null === $authorization) {
            return false;
        }

        return str_starts_with($authorization, 'Bearer ');
    }

    public function getUser(string $userIdentifier, UserProviderInterface $userProvider): UserInterface
    {
        if ('' === $userIdentifier) {
            throw new AuthenticationException(
                'User identifier is not provided. Post request field name: oauth_user_id',
            );
        }

        return $userProvider->loadUserByIdentifier($userIdentifier);
    }

    public function checkCredentials(string $token, UserInterface $user): bool
    {
        return true;
    }

    public function createAuthenticatedToken(PassportInterface $passport, string $providerKey): TokenInterface
    {
        if (false === ($passport instanceof Passport)) {
            throw new LogicException('Token contains no user!');
        }

        $tokenUser = $passport->getUser();

        $oauth2Token = $this->oauth2TokenFactory->createOAuth2Token($this->psr7Request, $tokenUser, $providerKey);

        if (!$this->isAccessToRouteGranted($oauth2Token)) {
            throw InsufficientScopesException::create($oauth2Token);
        }

        $oauth2Token->setAuthenticated(true);

        /** @phpstan-ignore-next-line  This return library oauth2 token instead of symfony post auth token */
        return $oauth2Token;
    }

    private function isAccessToRouteGranted(OAuth2Token $token): bool
    {
        $routeScopes = $this->psr7Request->getAttribute('oauth2_scopes', []);

        if ([] === $routeScopes) {
            return true;
        }

        $tokenScopes = $token
            ->getAttribute('server_request')
            ->getAttribute('oauth_scopes');

        return [] === array_diff($routeScopes, $tokenScopes);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw $exception;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function authenticate(Request $request): PassportInterface
    {
        return new SelfValidatingPassport(new UserBadge($this->getCredentials($request)));
    }

    public function getCredentials(Request $request): string
    {
        try {
            $this->resourceServer->validateAuthenticatedRequest($this->psr7Request);
        } catch (OAuthServerException $e) {
            throw new AuthenticationException('The resource server rejected the request.', 401, $e);
        }

        return $this->psr7Request->getAttribute('oauth_user_id');
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
