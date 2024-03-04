<?php

namespace App\Controller;

use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class GoogleController extends AbstractController
{

    #[route('/connect/google', name:'connect_google_start')]
    public function connectAction(ClientRegistry $clientRegistry)
    {

        return $clientRegistry
            ->getClient('google') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([],[]); // the scopes you want to access
    }

    #[route('/connect/google/check', name:'connect_google_check')]

    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {

        // return $this->redirectToRoute('app_home'); 

        $client = $clientRegistry->getClient('google');

        try {
            // the exact class depends on which provider you're using
            /** @var GoogleUser $user */
            $user = $client->fetchUser();

            // do something with all this new power!
            // e.g. $name = $user->getFirstName();
            var_dump($user); die;
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            var_dump($e->getMessage()); die;
        }

    }

}
