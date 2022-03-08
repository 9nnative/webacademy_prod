<?php

namespace App\Controller;


use DateTimeZone;
use App\Entity\Event;
use App\Entity\Links;
use App\Entity\Course;
use App\Entity\Section;
use App\Form\GroupType;
use App\Form\LinksType;
use App\Entity\Category;
use App\Form\CourseType;
use App\Entity\UserGroup;
use App\Form\ProductType;
use App\Form\SectionType;
use App\Form\CategoryType;
use App\Entity\CourseFiles;
use App\Entity\GroupPrompt;
use App\Entity\Notification;
use App\Entity\InviteToGroup;
use App\Form\GroupPromptType;
use App\Form\NewUserGroupType;
use AppBundle\Entity\Document;
use App\Form\CourseSectionType;
use Doctrine\ORM\EntityManager;
use App\Entity\CourseNaviguation;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ELearningController extends AbstractController
{
    /**
     * @Route("/elearning", name="elearning")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {   
        if($this->getUser()){
            $userscourses = $this->getUser()->getCourses();
        }else{
            $userscourses = 0;
        }

        $courseRepository = $this->getDoctrine()->getRepository(Course::class);
        $allcourses = array_reverse($courseRepository->findBy([
            'state' => 'published',
        ]));

        $pagination = $paginator->paginate(
            $allcourses, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );  

        $categoriesRepository = $this->getDoctrine()->getRepository(Category::class);
        $allcategories = $categoriesRepository->findAll();


        return $this->render('e_learning/index.html.twig', [
            'userscourses'=> $userscourses,
            'allcourses' => $allcourses,
            'allcategories' => $allcategories,
            'pagination' => $pagination,
        ]);
    }
    /**
     * @Route("/acceptInvite/{id}", name="acceptinvite")
     */
    public function acceptInvite(InviteToGroup $invitation, Request $request): Response
    {   
        $currentUser = $this->getUser();

        $notification = $invitation->getNotification();

        if($notification->getUser() == $currentUser){

        $entityManager = $this->getDoctrine()->getManager();
        $invitation->setStatus(1);
        $notification->setStatus(1);
        $entityManager->persist($invitation);
        $entityManager->persist($notification);
        $entityManager->flush();

        $courseSlug = $invitation->getCourse()->getSlug();

        $this->addFlash('ui success message', 'Succès - Vous faites désormais parti du groupe de travail');

        return $this->redirectToRoute('course_details', ['slug' =>  $courseSlug]);
                    
        }else{

        $this->addFlash('ui error message', 'Erreur - Cette notification ne vous est pas destinée');
        return $this->redirect($request->headers->get('referer'));

        }
    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT')")
     * @Route("/toggleFileVisibility/{filename}", name="togglefilevisibility")
     */
    public function toggleVisbility(CourseFiles $courseFile, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($courseFile->getIsReadable() == true){
            $courseFile->setIsReadable(false);
            $entityManager->persist($courseFile);
            $entityManager->flush();
            $this->addFlash('ui success message', 'Le document est désormais invisible');
            return $this->redirect($request->headers->get('referer'));
        }else{
            $courseFile->setIsReadable(true);
            $entityManager->persist($courseFile);
            $entityManager->flush();
            $this->addFlash('ui success message', 'Le document est désormais visible');
            return $this->redirect($request->headers->get('referer'));
        }
    }

    /**
     * @Route("/postAcceptor", name="postacceptor")
     */
    public function postAcceptor()
    {
    /***************************************************
   * Only these origins are allowed to upload images *
   ***************************************************/
    $accepted_origins = array("http://localhost", "http://192.168.1.1", "https://innovationwebacademy.com", "https://webacademy.hidora.com");

    /*********************************************
     * Change this line to set the upload folder *
     *********************************************/
    $imageFolder = "uploads/coursefiles/";

    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // same-origin requests won't set an origin. If the origin is set, it must be valid.
        if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        } else {
        header("HTTP/1.1 403 Origin Denied");
        return;
        }
    }

    // Don't attempt to process the upload on an OPTIONS request
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        header("Access-Control-Allow-Methods: POST, OPTIONS");
        return;
    }

    reset ($_FILES);
    $temp = current($_FILES);
    if (is_uploaded_file($temp['tmp_name'])){

    // Sanitize input
    if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
        header("HTTP/1.1 400 Invalid file name.");
        return;
    }

    // Verify extension
    if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png", "mp4", "avi"))) {
        header("HTTP/1.1 400 Invalid extension.");
        return;
    }

    // Accept upload if there was no origin, or if it is an accepted origin
    $filetowrite = $imageFolder . $temp['name'];
    move_uploaded_file($temp['tmp_name'], $filetowrite);

    // Determine the base URL
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? "https://" : "http://";
    $baseurl = $protocol . $_SERVER["HTTP_HOST"] . rtrim(dirname($_SERVER['REQUEST_URI']), "/") . "/";

    // Respond to the successful upload with JSON.
    // Use a location key to specify the path to the saved image resource.
    // { location : '/your/uploaded/image/file'}
    echo json_encode(array('location' => $baseurl . $filetowrite));

    } else {
        // Notify editor that the upload failed
        header("HTTP/1.1 500 Server Error");

        return new JsonResponse("failed");
    }

        return new JsonResponse("success");
    }
    /**
     * @Route("/addCourseToFavorites/{slug}", name="addtofavorites")
     */
    public function addFavorite(Course $course, Request $request): Response
    {   
        if($this->getUser()){

       
        $user = $this->getUser();

        $favorites = $user->getFavorites();

        $entityManager = $this->getDoctrine()->getManager();
        
        if($favorites->contains($course)){
            $user->removeFavorite($course);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('ui success message', 'Vous avez bien supprimé ce cours de vos favoris');
            return $this->redirect($request->headers->get('referer'));

        }else{
            $user->addFavorite($course);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('ui success message', 'Vous avez bien ajouté ce cours à vos favoris');
            return $this->redirect($request->headers->get('referer'));
        }

        }else{
            $this->addFlash('ui error errorPrompt message', '⚠️ Vous devez être connecté pour effectuer cette action');
            return $this->redirect($request->headers->get('referer'));
        }
    }
    
    /**
     * @Route("/course/{slug}", name="course_details")
     */

    public function courseDetails(Course $course, Request $request, FormFactoryInterface $formFactory, SluggerInterface $slugger): Response
    {   

        $naviguationRepository = $this->getDoctrine()->getRepository(CourseNaviguation::class);

        $naviguation = $naviguationRepository->findOneBy([
            'user' => $this->getUser(),
            'course' => $course
        ]);

        // if(!$naviguation){
        //     $naviguation = new CourseNaviguation();
        //     $naviguation->setUser($this->getUser());
        //     $naviguation->setCourse($course);
        // }
        
        $newUsergroup = new UserGroup;
        $newUsergroupForm = $this->createForm(NewUserGroupType::class, $newUsergroup);
        $newUsergroupForm->handleRequest($request);

        $usergroup = $course->getWgroup();

        $inviteRepo = $this->getDoctrine()->getRepository(InviteToGroup::class);
        $allinvites = $inviteRepo->findAll();

        $formsEditG = array();

        $formsNewMsg = array();

        $viewFormNewMsg = array();

        $views = array(); 

        $course->setViews($course->getViews() + 1);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($course);
        $entityManager->flush(); 

        $i = 0;

        $time = new \DateTime('now', new DateTimeZone('Europe/Paris'));

        $groupprompts = null;

        $groupPromptForm = null;

        foreach($usergroup as $group){

            $i++;

            $newgroupPrompt = new GroupPrompt();
            $newgroupPrompt->setWgroup($group);

            $usergroupForm = $this->createForm(GroupType::class, $group);
            $groupPromptForm = $this->createForm(GroupPromptType::class, $newgroupPrompt);
            $groupPromptForm = $formFactory->createNamed($i, GroupPromptType::class, $newgroupPrompt);
            
            $groupprompts = $group->getGroupPrompts();

            $views[] = $usergroupForm->createView();

            $viewFormNewMsg[] = $groupPromptForm->createView();

            $formsEditG[] = $usergroupForm;

            $formsNewMsg[] = $groupPromptForm;

            $time = new \DateTime('now', new DateTimeZone('Europe/Paris'));
        }

            foreach($formsNewMsg as $groupPromptForm){

                $groupPromptForm->handleRequest($request);
            
                if ($groupPromptForm->isSubmitted() && $groupPromptForm->isValid()) { 
                
                                /** @var UploadedFile $brochureFile */
                        $brochureFile = $groupPromptForm->get('brochure')->getData();

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
                                    $this->getParameter('uploads'),
                                    $newFilename
                                );
                            } catch (FileException $e) {
                                // ... handle exception if something happens during file upload
                            }

                            // updates the 'brochureFilename' property to store the PDF file name
                            // instead of its contents
                            $newgroupPrompt->setBrochureFilename($newFilename);
                        }

                    $newgroupPrompt->setWgroup($group);
                    $entityManager = $this->getDoctrine()->getManager();
    
                    foreach($group->getUsers() as $user){

                        $notification = new Notification();
                        $notification->addUser($user);
                        $notification->setHeader('Nouveau message de groupe');
                        $notification->setContent($group->getName());
                        $notification->setLink('/course/'.$course->getSlug());
                        $notification->setDate($time);
        
                        $entityManager->persist($notification);
                    }
    
                    $entityManager->persist($newgroupPrompt);
                    $entityManager->flush(); 
    
                    $this->addFlash('ui success message', 'Succès - Message créé!');
    
                    return $this->redirectToRoute('course_details', ['slug' => $course->getSlug()]);
                }

            }

            foreach($formsEditG as $usergroupForm){

                $usergroupForm->handleRequest($request);
                if (!$usergroupForm->isSubmitted()) continue;

                if ($usergroupForm->isValid()){
                    
                    foreach($usergroupForm->get('users')->getData() as $user){

                        $repository = $this->getDoctrine()->getRepository(InviteToGroup::class);

                        $isalreadyInvited = $repository->findOneBy([
                            'user' => $user->getId(),
                            'wgroup' => $group->getId(),
                        ]);
                
                        if($isalreadyInvited == false){

                            $newInviteToGroup = new InviteToGroup();
                            $newInviteToGroup->setStatus(0);
                            $newInviteToGroup->setUser($user);
                            $newInviteToGroup->setWgroup($group);
                            $newInviteToGroup->setCourse($course);

                            $notification = new Notification();
                            $notification->addUser($user);
                            $notification->setInviteToGroup($newInviteToGroup);
                            $notification->setHeader('Invitation à un groupe');
                            $notification->setContent('Vous avez été invité à rejoindre le groupe de travail '.$group->getName());
                            $notification->setLink('/course/'.$course->getSlug());
                            $notification->setDate($time);

                            $entityManager = $this->getDoctrine()->getManager();
                            $entityManager->persist($notification);
                            $entityManager->persist($newInviteToGroup);
                            $entityManager->flush(); 
                        }
                    
                    }

                    $event = new Event();
                    $event->setActionby($this->getUser());
                    $event->setType("edit");
                    $event->setDescription("a modifié le groupe");
                    $event->setWgroup($group);
                    $event->setDate($time);

                    $group->setCreatedBy($this->getUser());

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($group);
                    $entityManager->persist($event);
                    $entityManager->flush();

                    $this->addFlash('ui success message', 'Succès - Groupe modifié !');
                
                    return $this->redirectToRoute('course_details', ['slug' => $course->getSlug()]);
                }

        }

        if ($newUsergroupForm->isSubmitted() && $newUsergroupForm->isValid()) { 

            $event = new Event();
            $event->setActionby($this->getUser());
            $event->setType("new");
            $event->setDescription("a créé le groupe");
            $event->setWgroup($newUsergroup);
            $event->setDate($time);

            $course->addWgroup($newUsergroup);

            $newUsergroup->setCreatedBy($this->getUser());

            foreach($newUsergroupForm->get('users')->getData() as $user){

                $newInviteToGroup = new InviteToGroup();

                $newInviteToGroup->setUser($user);
                $newInviteToGroup->setCourse($course);
                $newInviteToGroup->setStatus(0);
                $newInviteToGroup->setWgroup($newUsergroup);
                $entityManager->persist($newInviteToGroup);
                $entityManager->flush();

                $notification = new Notification();
                $notification->addUser($user);
                $notification->setInviteToGroup($newInviteToGroup);
                $notification->setHeader('Invitation à un groupe');
                $notification->setContent('Vous avez été invité à rejoindre le groupe de travail'.$newUsergroup->getName());
                $notification->setLink('/startCourse/'.$course->getSlug());
                $notification->setDate($time);

                $entityManager = $this->getDoctrine()->getManager();
                
                $entityManager->persist($notification);
                $entityManager->flush();
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newUsergroup);
            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('ui success message', 'Succès - Groupe créé !');
        
            return $this->redirect($request->headers->get('referer'));
        }       

        return $this->render('e_learning/detail.html.twig', [
            'course'=> $course,
            'formsEditG' => $views,
            'viewFormNewMsg' => $viewFormNewMsg,
            'usergroup' => $usergroup,
            'groupprompts' => $groupprompts,
            'allinvites' => $allinvites,
            'newUsergroupForm' => $newUsergroupForm->createView(),
            'groupPromptForm' => $groupPromptForm,
            'naviguation' => $naviguation
        ]);
    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT') ")
     * @Route("/publishcourse/{slug}", name="publishCourse")
     */
    public function publishCourse(Course $course, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($course->getSections() == null){
            $this->addFlash('ui error message sucessFlash', 'Erreur - Le cours doit au minimum contenir une section');
        }
        
        $course->setState("published");

        foreach($course->getSections() as $section){
            $course->addSection($section);
            $entityManager->persist($section);
            $entityManager->flush($section);
        }

        $entityManager->persist($course);
        $entityManager->flush($course);
        $this->addFlash('ui success message sucessFlash', 'Succès - Le cours a correctement été publié !');

        return $this->redirectToRoute('course_details', ['slug' => $course->getSlug()]);

    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT')")
     * @Route("/update_course_title/{id}", name="update_course_title")
     */
    public function update_course_title(Course $course, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isXMLHttpRequest()) {  
            
            $title = $_POST['title'];

    
            $course->setTitle($title);


            $entityManager->persist($course);
            $entityManager->flush($course);

            $message="Titre mis à jour";

            $newSlug = $course->getSlug();

            return new JsonResponse(array('msg' => $message,'updatedSlug' => $newSlug));
        }

        return new Response;
    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT')")
     * @Route("/toggle_IsHidden/{id}", name="toggle_IsHidden")
     */
    public function toggle_IsHidden(Section $section, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isXMLHttpRequest()) {  
            
            if($section->getIsHidden() == 1){
                $section->setIsHidden(0);
                $entityManager->persist($section);
                $entityManager->flush();
            }else{
                $section->setIsHidden(1);
                $entityManager->persist($section);
                $entityManager->flush();
            }
            
        }
        return new Response;
    }
    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT') ")
     * @Route("/autoSaveSections/{id}", name="autosave_sections")
     */
    public function autoSaveSections(Course $course, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isXMLHttpRequest()) {

            $date = new \DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));

            $cuurrentformateddate = date_format($date, 'Y-m-d H:i:s');

            $post = parse_str($_POST['course_section'], $params);

            return new JsonResponse(array('date' => $cuurrentformateddate,'post' => $params));
        }

        return new Response();
    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT') ")
     * @Route("/autoSaveNewSection/{slug}", name="autosave_new_section")
     */
    public function autoSaveSection(Course $course, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isXMLHttpRequest()) {  
                        
            $date = new \DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));

            $cuurrentformateddate = date_format($date, 'Y-m-d H:i:s');

            $content = $_POST['content'];

            $title = $_POST['title'];

            $autosavedsection = $course->getAutosavedSection();

            $autosavedsection->setContent($content);
            $autosavedsection->setTitle($title);
            $autosavedsection->setCourse($course);

            $entityManager->persist($autosavedsection);
            $entityManager->flush();


            return new JsonResponse(array('date' => $cuurrentformateddate));

        }

        return new Response();

    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT') ")
     * @Route("/deletecourse/{slug}", name="deleteCourse")
     */
    public function deleteCourse(Course $course, EntityManagerInterface $entityManager, Request $request): Response
    {
        $course->setState("archived");

        $entityManager->persist($course);

        $entityManager->flush($course);

        $this->addFlash('ui success message sucessFlash', 'Succès - Le cours a correctement été supprimé !');

        return $this->redirectToRoute('course_details', ['slug' => $course->getSlug()]);
    }
    
    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT') ")
     * @Route("/newcourse", name="newCourse")
     * @Route("/course/edit/{slug}", name="editCourse")
     */
    public function newCourse(Request $request, Course $course = null, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {

        if(!$course){

            $course = new Course();
            $course->setAutosave(1);

            $section = new Section();

            $sectionForm = $this->createForm(SectionType::class, $section);
            $sectionForm->handleRequest($request);

        }else{

            $existingSections = $course->getSections();

            $section = new Section();

            $sectionForm = $this->createForm(SectionType::class, $section);
            $sectionForm->handleRequest($request);

        }

        $courseForm = $this->createForm(CourseType::class, $course);
        $courseForm->handleRequest($request);
       
        if ($courseForm->getClickedButton() === $courseForm->get('suivant')){

            if($courseForm->isSubmitted() && $courseForm->isValid()) {

                /** @var UploadedFile $CourseProfilePicFile */
                $CourseProfilePicFile = $courseForm->get('brochure')->getData();

                if(!$CourseProfilePicFile){
                    $course->setBrochureFilename('undraw_Online_learning_re_qw08.svg');
                }

                // this condition is needed because the 'brochure' field is not required
                // so the PDF file must be processed only when a file is uploaded
                if ($CourseProfilePicFile) {
                    $originalFilename = pathinfo($CourseProfilePicFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$CourseProfilePicFile->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $CourseProfilePicFile->move(
                            $this->getParameter('coursesimages_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $course->setBrochureFilename($newFilename);
                }

                $currentdate = new \DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));
                $course->setCreatedAt($currentdate);
                $course->setIsPublished(0);
                $course->setState('saved');
                $course->setStep(2);
                $course->setCreatedBy($this->getUser());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($course);
                $entityManager->flush();

                return $this->redirectToRoute('createsections', ['id' => $course->getId()]);
            }
        }


        return $this->render('e_learning/creator/new.html.twig', [
            'courseForm' => $courseForm->createView(),
            'sectionForm' => $sectionForm->createView(),
            'course'=> $course,
        ]);
    }

        
    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT')")
     * @Route("/createCategory", name="createCategory")
     */
    public function createCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $newCategory = new Category();

        $categoriesRepository = $this->getDoctrine()->getRepository(Category::class);
        $allCategories = $categoriesRepository->findAll();

        $categoryForm = $this->createForm(CategoryType::class, $newCategory);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newCategory);
            $entityManager->flush();

            $this->addFlash('ui success message sucessFlash', 'Succès - Catégorie créée !');

            return $this->redirect($request->headers->get('referer'));

            }

            return $this->render('e_learning/creator/newCategory.html.twig', [
                'categoryForm' => $categoryForm->createView(),
                'allCategories' => $allCategories,
            ]);
    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT') ")
     * @Route("/deleteCategory/{id}", name="delete_category", requirements={"id"="\d+"})
     */
    public function deleteCategory(Request $request, Category $category, EntityManagerInterface $entityManager)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash('ui success message sucessFlash', 'Catégorie supprimée !');

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT')")
     * @Route("/ajax/snippet/image/send", name="ajax_snippet_image_send", methods={"GET", "POST"})
     */
    public function ajaxSnippetImageSendAction(Request $request, EntityManagerInterface $em)
    {

        $document = new Document();
        $media = $request->files->get('file');

        $document->setFile($media);
        $document->setPath($media->getPathName());
        $document->setName($media->getClientOriginalName());
        $document->upload();
        $em->persist($document);
        $em->flush();

        //infos sur le document envoyé
        //var_dump($request->files->get('file'));die;
        return new JsonResponse(array('success' => true));
    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT') ")
     * @Route("/createSections/course/{id}", name="createsections")
     * @Route("/editSections/course/{id}", name="editsections")
     */
    public function createSection(Request $request, Course $course = null, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        // return $this->redirectToRoute('course_details', ['slug' => $course->getSlug()]);
  
        $linksRepository = $this->getDoctrine()->getRepository(Links::class);

        $existingLinks = $linksRepository->findBy([
            'section' => null,
            'state' => 'inform',
            'user' => $this->getUser()
        ]);

        $existingSections = $course->getSections();

        $section = new Section();
        
        $sectionForm = $this->createForm(SectionType::class, $section);
        $sectionForm->handleRequest($request);
        
            if ($sectionForm->isSubmitted() && $sectionForm->isValid()){

                // On boucle sur les fichiers
                foreach($sectionForm->get('courseFiles')->getData() as $courseFile){

                    $originalFilename = pathinfo($courseFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$courseFile->guessExtension();
                    $type = $courseFile->guessExtension();
                    // Move the file to the directory where brochures are stored
                    try {
                        $courseFile->move(
                            $this->getParameter('coursefiles_directory'),
                            $newFilename
                        );

                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    
                    // On crée l'image dans la base de données
                    $courseFile = new CourseFiles();
                    $courseFile->setType($type);
                    $courseFile->setIsReadable(true);
                    $courseFile->setFilename($newFilename);
                    $section->addCourseFile($courseFile);
                    $section->setCourse($course);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($courseFile);
                    $entityManager->persist($section);
                    $entityManager->persist($course);
                    $entityManager->flush();
                }

                $section->setCourse($course);

                $entityManager->persist($section);
                $entityManager->persist($course);

                $entityManager->flush($section);
                $entityManager->flush($course);

                return $this->redirect($request->headers->get('referer'));
                // unset($section);
                // unset($sectionForm);

                // $section = new Section();
                // $section->setTitle('');
                // $section->setContent('');
                // $section->setCourse($course);

                // $entityManager->persist($course);
                // $entityManager->flush($course);
            }

        $courseSectionForm = $this->createForm(CourseSectionType::class, $course);
        $courseSectionForm->handleRequest($request);

        if ($courseSectionForm->isSubmitted() && $courseSectionForm->isValid()){
            
            foreach($course->getSections() as $section){
                $entityManager->persist($section);
                $entityManager->flush($section);
            }

            $entityManager->persist($course);

            $entityManager->flush($course);  

            $this->addFlash('ui success message sucessFlash', 'Les sections ont été mises à jour');        
        }

        return $this->render('e_learning/creator/sections.html.twig', [
           'course' => $course,
           'sectionForm' => $sectionForm->createView(),
           'existingSections' => $existingSections,
           'existingLinks' => $existingLinks,
           'courseSectionForm' => $courseSectionForm->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT') ")
     * @Route("/deletesection/{id}", name="delete_section", requirements={"id"="\d+"})
     */
    public function deleteSection(Section $section, EntityManagerInterface $entityManager, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        foreach($section->getLinks() as $link){
            $entityManager->remove($link);
        }
        foreach($section->getCourseNaviguations() as $naviguation){
            $entityManager->remove($naviguation);
        }
        foreach($section->getCourseFiles() as $file){
            $entityManager->remove($file);
        }

        // kill autosave
        $course = $section->getCourse();
        
        $course->setAutosavedSection(null);
        
        $this->addFlash('ui success message sucessFlash', 'Succès - Section supprimée !');

        $entityManager->remove($section);
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT') ")
     * @Route("/deletelink/{id}", name="delete_link", requirements={"id"="\d+"})
     */
    public function deleteLink(Links $link, EntityManagerInterface $entityManager)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($link);
        $entityManager->flush();

        return new Response();
    }

    /**
     * @Security("is_granted('ROLE_COACH') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_EXPERT') ")
     * @Route("/deletePromptMessage/{id}", name="delete_message", requirements={"id"="\d+"})
     */
    public function deletePromptMessage(GroupPrompt $gp, EntityManagerInterface $entityManager, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($gp);
        $entityManager->flush();

        $this->addFlash('ui success message sucessFlash', 'Succès - Message supprimé !');

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/course/{slug}/navigate/{section_id}", name="navigate")
     * @Entity("section", expr="repository.find(section_id)")
     */
    public function naviguate(Course $course, Section $section, EntityManagerInterface $entityManager, Request $request)
    {
        $naviguationRepository = $this->getDoctrine()->getRepository(CourseNaviguation::class);

        $naviguation = $naviguationRepository->findOneBy([
            'user' => $this->getUser(),
            'course' => $course
        ]);

        $naviguation->setSection($section);

        $entityManager->persist($section);
        $entityManager->flush();


        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/startCourse/{slug}", name="start_course")
     */
    public function startCourse(Course $course, EntityManagerInterface $entityManager, Request $request)
    {

        if($this->getUser()){

            $naviguationRepository = $this->getDoctrine()->getRepository(CourseNaviguation::class);

            $naviguation = $naviguationRepository->findOneBy([
                'user' => $this->getUser(),
                'course' => $course
            ]);

            if($naviguation){

                if($naviguation->getStarted() == 1){
                    
                    $naviguation->setStarted(0);
            
                    $naviguation->setStep("");

                    $firstSection = $course->getSections()->first();

                    $naviguation->setSection($firstSection);

                    $naviguation->setStarted(1);

                    $naviguation->setCourse($course);
                    
                    $naviguation->setUser($this->getUser());
                
                    $naviguation->setStep("init");

                    $entityManager->persist($naviguation);

                    $entityManager->flush();

                }else{
            
                    $firstSection = $course->getSections()->first();

                    $naviguation->setSection($firstSection);

                    $naviguation->setStarted(1);

                    $naviguation->setCourse($course);
                    
                    $naviguation->setUser($this->getUser());
                
                    $naviguation->setStep("init");

                    $entityManager->persist($naviguation);

                    $entityManager->flush();
                }

            }else{

                $inviteToGroupRepo = $this->getDoctrine()->getRepository(InviteToGroup::class);

                $invite = $inviteToGroupRepo->findOneBy([
                    'user' => $this->getUser(),
                    'course' => $course
                ]);

                $naviguation = new CourseNaviguation();

                $firstSection = $course->getSections()->first();

                $naviguation->setSection($firstSection);

                $naviguation->setInviteToGroup($invite);

                $naviguation->setStarted(1);

                $naviguation->setCourse($course);
                
                $naviguation->setUser($this->getUser());
            
                $naviguation->setStep("init");

                $entityManager->persist($naviguation);

                $entityManager->flush();

            }

        }

        return $this->redirectToRoute('course_details', ['slug' =>  $course->getSlug()]);
       
    }
}
