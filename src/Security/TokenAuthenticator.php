<?php


namespace App\Security;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

class TokenAuthenticator extends AbstractAuthenticator
{

    private $em;

    /**
     * TokenAuthetcator constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em= $em;
    }


    /**
     * @inheritDoc
     * Appelé a chaque requete, determine si authentification doit etre utilisé pour la requete. Retourner faux passe l'authentification
     */
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }

    /**
     * @inheritDoc
     * Appelé a chaque requete. Quelque soit les informations d'identification, elles sont renvoyé a getUser en tant que $credentials
     */
    public function authenticate(Request $request): PassportInterface
    {
        return $request->headers->get('X-AUTH-TOKEN');

    }

    public function getUser($credentials, UserProviderInterface $userProvider){
        if(null=== $credentials){
            //le token est vide, authentification echoue avec statut 401
            return null;
        }
        //"username" dans ce cas est apitoken, voir la clef `property` dans `your_db_provider` dans `security.yaml`
        //si ca retourne un utilisateur, la fonction checkCredentials() est appelé
        return $userProvider->loadUserByUsername($credentials);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Check credentials - e.g. make sure the password is valid.
        // In case of an API token, no credential check is needed.

        // Return `true` to cause authentication success
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        //retourne rien pour laisser passer la requete
        return null;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            // you might translate this message
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
