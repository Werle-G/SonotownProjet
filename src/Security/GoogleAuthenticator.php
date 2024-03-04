<?Php

namespace App\Security;

use GuzzleHttp\Client;
use App\Entity\User; 
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

    class GoogleAuthenticator extends OAuth2Authenticator implements AuthenticationEntrypointInterface
    {

        private ClientRegistry $clientRegistry;
        private EntityManagerInterface $entityManager;
        private RouterInterface $router;

        public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router)
        {
            $this->clientRegistry = $clientRegistry;
            $this->entityManager = $entityManager;
            $this->router = $router;
        }

        public function supports(Request $request): ?bool
        {
            // continue ONLY if the current ROUTE matches the check ROUTE
            return $request->attributes->get('_route') === 'connect_google_check';
        }


        public function authenticate(Request $request): Passport
        {
            $client = $this->clientRegistry->getClient('google');
            $accessToken = $this->fetchAccessToken($client);

          
            return new SelfValidatingPassport(

                new UserBadge($accessToken->getToken(), function () use ($accessToken, $client) {
                    /** @var GoogleUser $googleUser */
                    $googleUser = $client->fetchUserFromToken($accessToken);

                    $email = $googleUser->getEmail();
                    
                    // dd($googleUser);
                    // have they logged in with Google before? Easy!
                    $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['googleId' => $googleUser->getId()]);

                    //User doesnt exist, we create it !
                    if (!$existingUser) {
                        $existingUser = new User();
                        $existingUser->setEmail($email);
                        $existingUser->setGoogleId($googleUser->getId());
                        $existingUser->setHostedDomain($googleUser->getHostedDomain());
                        // $existingUser->setPassword($googleUser->getPassword());
                        $this->entityManager->persist($existingUser);
                    }
                    $existingUser->setAvatar($googleUser->getAvatar());
                    $this->entityManager->flush();

                    return $existingUser;
                })
            );
        }

        // /**
        //  * @return Client
        //  */
        // private function getClient()
        // {
        //     return $this->clientRegistry
        //         // "facebook_main" is the key used in config/packages/knpu_oauth2_client.yaml
        //         ->getClient('google_main');
        // }

        public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
        {

            // change "app_dashboard" to some route in your app
            return new RedirectResponse(
                $this->router->generate('app_home')
            );

            // or, on success, let the request continue to be handled by the controller
            //return null;
        }

        public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
        {
            $message = strtr($exception->getMessageKey(), $exception->getMessageData());

            return new Response($message, Response::HTTP_FORBIDDEN);
        }

        /**
         * Called when authentication is needed, but it's not sent.
         * This redirects to the 'login'.
         */
        public function start(Request $request, AuthenticationException $authException = null): Response
        {
            // return new RedirectResponse(
            //     '/connect/', // might be the site, where users choose their oauth provider
            //     Response::HTTP_TEMPORARY_REDIRECT
            // );
            return new RedirectResponse('/login');
        }
}