<?php

namespace App\Controller;

use DateTimeZone;
use App\Entity\User;
use App\Service\LinkedinService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SecurityController extends AbstractController
{   /**
    * @var TokenStorageInterface
    */
   private $tokenStorage;

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    /**
     * @Route("/login/linkedin", name="app_login_linkedin", methods={"GET", "POST"})
     * @param Request $request
     * @param LinkedinService $service
     * @param EntityManagerInterface $em
     * @param tokenStorage $tokenStorage
     * @return Response
     */
    public function loginLinkedIn(Request $request, LinkedinService $service, EntityManagerInterface $em): Response
    {
        $time = new \DateTime('now', new DateTimeZone('Europe/Paris'));

        if ($this->getUser()) {
            throw new AccessDeniedHttpException();
        }

        $code = $request->query->get('code');
        if ($code === null) {
            return $service->redirectToLinkedin();
        }

        if (!$service->isAuthenticated($request)) {
            $this->addFlash('error', 'Une erreur est survenue');
            return $this->redirectToRoute('app_login');
        }

        try {
            /**
             * @var LinkedInResourceOwner $userLinkedIn
             */
            $userLinkedIn = $service->getUser($code);
            $user = $em->getRepository(User::class)->findOneBy(['email' => $userLinkedIn->getEmail()]);
            if (!$user instanceof User) {
                $user = (new User())
                ->setEmail($userLinkedIn->getEmail())
                ->setName($userLinkedIn->getLastName())
                ->setForename($userLinkedIn->getFirstName())
                ->setInscriptionDate($time);
                // ->setBio($userLinkedIn->getHeadline())
                // ->setBrochureFilename($userLinkedIn->getDisplayImage());
                dump($userLinkedIn);
                dump($userLinkedIn->getSortedProfilePictures());
                $pp = $userLinkedIn->getSortedProfilePictures();
                dump(end($pp));
                $pp = (end($pp));
                $url = $pp['url'];
                dump($url);
                $user->setBrochureFilename($pp['url']);
                die;
                $em->persist($user);
                $em->flush();
                $this->addFlash('ui message success sucessFlash', 'Votre compte a bien été créé');
            }

            // $token = new UsernamePasswordToken($user, null, 'main', $this->providerKey);

            // $this->tokenStorage->setToken($token);
            // $this->session->set('_security_main',
            // serialize($token));
            // $user = (new User())
            //         ->setEmail($userLinkedIn->getEmail())
            //         ->setName($userLinkedIn->getFirstName())
            //         ->setPassword('test');

            //     $em->persist($user);
            //     $em->flush();

        } catch (IdentityProviderException $e) {
            $this->addFlash('error', 'Une erreur est survenue');
        }

        return $this->redirectToRoute('landingpage');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
