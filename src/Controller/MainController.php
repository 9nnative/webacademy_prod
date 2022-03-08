<?php

namespace App\Controller;

use Exception;
use DateTimeZone;
use App\Entity\User;
use App\Form\CVType;
use App\Entity\Event;
use App\Entity\Links;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Entity\Access;
use App\Entity\Course;
use App\Entity\Ticket;
use App\Entity\Message;
use App\Entity\Section;
use App\Form\GroupType;
use App\Form\LinksType;
use App\Form\TicketType;
use App\Entity\UserGroup;
use App\Form\MessageType;
use App\Form\LinkedInType;
use App\Entity\Notification;
use App\Form\UserRightsType;
use App\Entity\InviteToGroup;
use App\Form\NotificationType;
use App\Form\CourseInGroupType;
use Doctrine\ORM\EntityManager;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class MainController extends AbstractController
{
    /**
     * @Route("/change_locale/{locale}", name="change_locale")
     */
    public function changeLocale($locale, Request $request)
    {
        // On stocke la langue dans la session
        $request->getSession()->set('_locale', $locale);

        // On revient sur la page précédente
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT')")
     * @Route("/togglenotifs", name="toggle_notifications")
     */
    public function toggleNotifs(Request $request)
    {   
        if (!$this->getUser()) {
            $this->addFlash('error errorPrompt', 'Veuillez vous connecter');
            return $this->redirectToRoute('app_login');
        }

        $currentUserNotifs = $this->getUser()->getNotifstate();

        if($currentUserNotifs == null){
            $this->getUser()->setNotifstate(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($this->getUser());
            $entityManager->flush();
        }else if($currentUserNotifs == 0){
            $this->getUser()->setNotifstate(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($this->getUser());
            $entityManager->flush();
        }else if($currentUserNotifs == 1){
            $this->getUser()->setNotifstate(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($this->getUser());
            $entityManager->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }
    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT')")
     * @Route("/updateispaying/{slug}", name="update_is_paying")
     */
    public function updateIsPaying(Request $request, Course $course): Response
    {
        if($course->getIsPaying() == 0){
            $course->setIsPaying(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->flush();
        }else{
            $course->setIsPaying(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }
    

    /**
     * @Route("/", name="landingpage")
     */
    public function index(): Response
    {
        
        return $this->render('main/index.html.twig');
    }
    /**
     * @Route("/checkJWT", name="checkJWT")
     */
    public function checkJWT(){
        
        $currentUserId = $this->getUser()->getId();

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

        $privateKey = "30658fd8d25b4c5f98305db3ca1e2ed9fcc10afff640c6fb99e4634e64681043";

        // NOTE: Before you proceed with the TOKEN, verify your users session or access.

        $payload = array(
        "sub" => $currentUserId, // unique user ID string
        "exp" => time() + 60 * 10 // 10 minute expiration
        );

        $jwt = JWT::encode($payload, $privateKey, 'HS256');
        $decoded = JWT::decode($jwt, new Key($privateKey, 'HS256'));

        print_r($decoded);

        try {
        $token = JWT::encode($payload, $privateKey, 'RS256');
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array("token" => $token));
        } catch (Exception $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo $e->getMessage();
        }

        return new Response();
    }


    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT')")
     * @Route("/acceptRequest/{id}", name="acceptRequest")
     */
    public function acceptRequest(Request $request, EntityManagerInterface $entityManager, Access $access ): Response
    {
        $user = $access->getUser();

        $user->setRoles(['ROLE_EXPERT']);
        $entityManager->persist($user);
        $entityManager->remove($access);
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/setTicketChecked/{id}", name="setTicketChecked")
     */
    public function setTicketDone(Request $request, EntityManagerInterface $entityManager, Ticket $ticket): Response
    {

        $ticket->setState('checked');

        $entityManager->persist($ticket);

        $entityManager->flush($ticket);

        $this->addFlash('ui error errorPrompt message', 'Ticket pris en charge');

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT')")
     * @Route("/setTicketPriority/{id}/{priority}", name="setTicketPriority")
     */
    public function setTicketPriority(Request $request, EntityManagerInterface $entityManager, Ticket $ticket): Response
    {
        $ticket->setState('checked');

        $entityManager->persist($ticket);

        $entityManager->flush($ticket);

        $this->addFlash('ui error errorPrompt message', 'Ticket pris en charge');

        return $this->redirect($request->headers->get('referer'));
    }


    /**
     * @Route("/favorites", name="favorites")
     */
    public function favorites(): Response
    {
        if($this->getUser()){
            $user = $this->getUser();
            $favorites = $user->getFavorites();
        }
    
        return $this->render('user/favorites.html.twig', [
            'favorites' => $favorites,
        ]);
    }

    /**
    * @Route("/update_notifications", name="update_notifications")
    */
    public function magasinId(Request $request, SerializerInterface $serializer)
    {
        if($request->isXmlHttpRequest()) {

            $notifications = $this->getUser()->getNotifs();

            $response = [];

            foreach($notifications as $notif){
            
                if($notif->getStatus() != 1){

                $date = $notif->getDate();
                $formatedDate = date_format($date, 'd/m/Y H:i:s');

                // <div class='ui message'><a href='/removeNotif/".$notif->getId()."' class='close icon marginleft'><i class='close icon'></i></a><div class='header'>".$notif->getHeader()."&nbsp;</div><a href=".$notif->getLink().">".$notif->getContent()."</a><br><small>".$formatedDate."</small></div>
                $response[] = [
                    'header' => $notif->getHeader(),
                    'content' => $notif->getContent(),
                    'id' => $notif->getId(),
                    'slug' => $notif->getWgroup()->getCourse()->getSlug(),
                    'date' => $formatedDate,
                    'type' => $notif->getType(),
                ];
                }
            }

            return new JsonResponse($response);
        }
        return new Response();
    }
    /**
     * @Route("/creatorDashboard", name="creatordashboard")
     */
    public function creatorDashboard(): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('error errorPrompt', 'Veuillez vous connecter');
            return $this->redirectToRoute('app_login');
        }

        $usersCourses = $this->getUser()->getCourses();

        return $this->render('main/creatordashboard.html.twig', [
            'usersCourses' => $usersCourses,
        ]);
    }

    /**
     * @Route("/update_lasttimeseen", name="update_lasttimeseen")
     */
    public function lasttimeseen()
    {
        $time = new \DateTime('now', new DateTimeZone('Europe/Paris'));
        if($this->getUser()){
            $user = $this->getUser();
            $user->setLasttimeseen($time);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return new Response();
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/adminpanel", name="adminpanel")
     */
    public function adminPanel(PaginatorInterface $paginator, Request $request, EntityManagerInterface $entityManager): Response
    {
        $usersRepository = $this->getDoctrine()->getRepository(User::class);
        $allusers = $usersRepository->findAll();

        $accessRepository = $this->getDoctrine()->getRepository(Access::class);
        $allAccess = $accessRepository->findAll();

        $ticketsRepository = $this->getDoctrine()->getRepository(Ticket::class);
        $allTickets = $ticketsRepository->findAll();

        $numMinutes = 1;

        $exactCurrenttime = new \DateTime('now', new DateTimeZone('Europe/Paris'));
        $time = new \DateTime('now', new DateTimeZone('Europe/Paris'));
        $time->modify ("-{$numMinutes} minutes");

        $newNotif = new Notification();

        $notifform = $this->createForm(NotificationType::class, $newNotif);
        $notifform->handleRequest($request);

        // setdate
        if ($notifform->isSubmitted() && $notifform->isValid()) { 
            $newNotif->setDate($exactCurrenttime);
            $entityManager->persist($newNotif);
            $entityManager->flush();

            $this->addFlash('ui success message', 'Notification envoyée !');
            return $this->redirect($request->headers->get('referer'));
        }

        $pagination = $paginator->paginate(
            $allusers, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            50 /*limit per page*/
        );    

        return $this->render('main/admin.html.twig', [
            'allusers' => $allusers,
            'time' => $time,
            'allTickets' => $allTickets,
            'pagination' => $pagination,
            'allAccess' => $allAccess,
            'notifform' => $notifform->createView(),
        ]);
    }

    /**
     * @Route("/userdetails", name="userdetails")
     */
    public function userDetails(Request $request, UserPasswordEncoderInterface $passwordEncoder, SluggerInterface $slugger): Response
    {
        if(!$this->getUser()){
            $this->addFlash('ui error errorPrompt message', 'Vous devez être connecté pour effectuer cette action');
            return $this->redirect($request->headers->get('referer'));
        }

        $currentUser = $this->getUser();
        $userForm = $this->createForm(RegistrationFormType::class, $currentUser);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) { 

            $ProfilePicFile = $userForm->get('profilepic')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($ProfilePicFile) {
                $originalFilename = pathinfo($ProfilePicFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$ProfilePicFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $ProfilePicFile->move(
                        $this->getParameter('profilepics_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $currentUser->setBrochureFilename($newFilename);
            }
            // encode the plain password
            $currentUser->setPassword(
                $passwordEncoder->encodePassword(
                    $currentUser,
                    $userForm->get('plainPassword')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($currentUser);
            $entityManager->flush();

            $this->addFlash('ui success message', 'Profil modifié !');
            return $this->redirect($request->headers->get('referer'));

        }

        $cvform = $this->createForm(CVType::class, $currentUser);
        $cvform->handleRequest($request);

        if ($cvform->isSubmitted() && $cvform->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $cvform->get('cv')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('cv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $currentUser->setCv($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($currentUser);
            $entityManager->flush();

            $this->addFlash('ui success message', 'CV Ajouté !');
            return $this->redirect($request->headers->get('referer'));
        }

        $linkedinForm = $this->createForm(LinkedInType::class, $currentUser);
        $linkedinForm->handleRequest($request);

        if ($linkedinForm->isSubmitted() && $linkedinForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($currentUser);
            $entityManager->flush();

            $this->addFlash('ui success message', 'Lien vers votre profil LinkedIn correctement ajouté !');
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('user/userdetails.html.twig', [
            'userForm' => $userForm->createView(),
            'cvForm' => $cvform->createView(),
            'linkedinForm' => $linkedinForm->createView()
        ]);
    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT')")
     * @Route("/removeUserX0", name="removeUser")
     */
    public function removeUser(): Response
    {   
        $currentuser = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($currentuser);
        $entityManager->flush();

        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/newTicket", name="newTicket")
     */
    public function newTicket(Request $request, EntityManagerInterface $entityManager): Response
    {   
        $newTicket = new Ticket();

        $ticketForm = $this->createForm(TicketType::class, $newTicket);
        $ticketForm->handleRequest($request);

        $date = new \DateTime('now', new DateTimeZone('Europe/Paris'));

        if ($ticketForm->isSubmitted() && $ticketForm->isValid()) { 
            if($this->getUser()){
                $newTicket->setCreatedBy($this->getUser());
            }
            $newTicket->setDate($date);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newTicket);
            $entityManager->flush();

            $this->addFlash('ui success message', 'Ticket envoyé !');
            return $this->redirect($request->headers->get('referer'));
        }


        return $this->render('support/newTicket.html.twig', [
            'ticketForm' => $ticketForm->createView() 
        ]);
    }

    /**
     * @Route("/askForAccess", name="askforaccess")
     */
    public function askForAccess(Request $request): Response
    {   
        if(!$this->getUser()){
            $this->addFlash('ui error errorPrompt message', '⚠️ Vous devez être connecté pour effectuer cette action');
            return $this->redirect($request->headers->get('referer'));
        }

        $currentUser = $this->getUser();

        $time = new \DateTime('now', new DateTimeZone('Europe/Paris'));

        if($currentUser->getCv() && $currentUser->getLinkedin()){

            $userAsks = $currentUser->getAccesses();

            $size = count($userAsks);

            if($size == 0){

                $access = new Access();
                $access->setUser($currentUser);
                $access->setDate($time);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($access);
                $entityManager->flush();
            }else{
                $this->addFlash('ui errorPrompt error message', 'Vous avez déjà effectué une demande !');
                return $this->redirect($request->headers->get('referer'));
            }
        }else{
            $this->addFlash('ui errorPrompt error message', 'Veuillez ajouter votre CV ainsi quun lien vers votre profil LinkedIn !');
            return $this->redirect($request->headers->get('referer'));
        }

        $this->addFlash('ui success message', 'Demande envoyée !');
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT')")
     * @Route("/editgroup/{slug}", name="editGroup")
     */
    public function groupDetails(UserGroup $usergroup, Request $request): Response
    {
        if(!$this->getUser()){
            $this->addFlash('ui error errorPrompt message', '⚠️ Vous devez être connecté pour effectuer cette action');
            return $this->redirect($request->headers->get('referer'));
        }
        
        $allmessages = $usergroup->getMessages();
        
        $usersingroup = $usergroup->getUsers();

        $message = new Message();
        $messageForm = $this->createForm(MessageType::class, $message);
        $messageForm->handleRequest($request);

        $time = new \DateTime('now', new DateTimeZone('Europe/Paris'));

        if ($messageForm->isSubmitted() && $messageForm->isValid()) { 

            $message->setWroteby($this->getUser());
            $message->setWgroup($usergroup);
            $message->setDate($time);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
            return $this->redirect($request->headers->get('referer'));

        }

        $links = new Links();
        $linkForm = $this->createForm(LinksType::class, $links);
        $linkForm->handleRequest($request);

        if ($linkForm->isSubmitted() && $linkForm->isValid()) { 
            
            $event = new Event();
            $event->setActionby($this->getUser());
            $event->setType(" a ajouté un lien au groupe");
            $event->setWgroup($usergroup);
            $event->setDate($time);

            $links->setWGroup($usergroup);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($links);
            $entityManager->persist($event);
            $entityManager->flush();
            return $this->redirect($request->headers->get('referer'));

        }

        $groupCourseForm = $this->createForm(CourseInGroupType::class, $usergroup);
        $groupCourseForm->handleRequest($request);

        if ($groupCourseForm->isSubmitted() && $groupCourseForm->isValid()) { 
            
            $event = new Event();
            $event->setActionby($this->getUser());
            $event->setType(" a ajouté des cours au groupe");
            $event->setWgroup($usergroup);
            $event->setDate($time);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usergroup);
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirect($request->headers->get('referer'));

        }

        $usergroupForm = $this->createForm(GroupType::class, $usergroup);
        $usergroupForm->handleRequest($request);

        $time = new \DateTime('now', new DateTimeZone('Europe/Paris'));

        if ($usergroupForm->isSubmitted() && $usergroupForm->isValid()) { 

            $event = new Event();
            $event->setActionby($this->getUser());
            $event->setType(" a modifié le groupe");
            $event->setWgroup($usergroup);
            $event->setDate($time);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usergroup);
            $entityManager->persist($event);
            $entityManager->flush();
        
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('usergroup/details.html.twig', [
            'group' => $usergroup,
            'usersingroup' => $usersingroup,
            'messageForm' => $messageForm->createView(),
            'linkForm' => $linkForm->createView(),
            'groupCourseForm' =>$groupCourseForm->createView(),
            'usergroupForm' => $usergroupForm->createView(),
            'allmessages' => $allmessages,
        ]);
    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT')")
     * @Route("/removeLink/{id}", name="removelink")
     */
    public function removeLink(Request $request, Links $link): Response
    {
        if($link){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($link);
        $entityManager->flush();
        }else{
            $this->addFlash('ui errorPrompt error message', 'Lien inexistant !');
        }
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/removeGroup/{slug}", name="removegroup")
     */
    public function removeGroup(Request $request, UserGroup $group): Response
    {
        if($group){
        $entityManager = $this->getDoctrine()->getManager();
        $invitesChild = $group->getInviteToGroups();

        foreach($invitesChild as $invite){
            $inviteId = $invite->getId();
            
            $entityManager->remove($invite);
            $entityManager->flush();
        }

        $entityManager->remove($group);
        $entityManager->flush();
        
        $this->addFlash('ui success message', 'Groupe supprimé !');
        }else{
            $this->addFlash('ui errorPrompt error message', 'Groupe inexistant !');
        }
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT')")
     * @Route("/removeCourse/{slug}/{group_id}", name="removecoursefromgroup")
     * @Entity("usergroup", expr="repository.find(group_id)")
     */
    public function removeCourseformGroup(Request $request, Course $course, UserGroup $usergroup): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $usergroup->removeCourse($course);
        $entityManager->persist($usergroup);
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/removeNotif/{id}", name="remove_notif")
     */
    public function removeNotification(Request $request, Notification $notification): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($notification);
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/toggleBlackMode", name="toggle_black_mode")
     */
    public function toggleBlackMode(Request $request): Response
    {
        if ($this->getUser()->getDarkMode() == null or 0){
            $this->getUser()->setDarkMode(1);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($this->getUser());
            $entityManager->flush();

            $this->addFlash('ui success message', 'Thème sombre activé ! Tes yeux te remercient');
        }else{
            $this->getUser()->setDarkMode(0);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($this->getUser());
            $entityManager->flush();
            $this->addFlash('ui success message', 'Thème sombre désactivé !');
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/toggleTutorial", name="toggle_tutorial")
     */
    public function toggleTutorial(Request $request): Response
    {
        if ($this->getUser()->getTutorial() == null or 0){
            $this->getUser()->setTutorial(1);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($this->getUser());
            $entityManager->flush();

            $this->addFlash('ui success message', 'Tutoriels activés !');
        }else{
            $this->getUser()->setTutorial(0);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($this->getUser());
            $entityManager->flush();
            $this->addFlash('ui success message', 'Tutoriels désactivés');
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/admineditprofile/{id}", name="admineditprofile")
     */
    public function userAdminEdit(User $user, Request $request): Response
    {   
        $userrightsForm = $this->createForm(UserRightsType::class, $user);
        $userrightsForm->handleRequest($request);

        $numMinutes = 1;

        $time = new \DateTime('now', new DateTimeZone('Europe/Paris'));
        $time->modify ("-{$numMinutes} minutes");

        if ($userrightsForm->isSubmitted() && $userrightsForm->isValid()) { 

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        
            $this->addFlash('ui success message', 'Les droits ont correctement été modifiés !');
            return $this->redirect($request->headers->get('referer'));
        }
        

        return $this->render('main/admineditprofile.html.twig', [
            'user'=> $user,
            'time'=> $time,
            'userrightsForm' => $userrightsForm->createView(),
        ]);
    }
}
