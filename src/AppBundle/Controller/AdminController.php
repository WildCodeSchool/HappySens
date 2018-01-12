<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 29/11/17
 * Time: 15:33
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Entity\UserHasSkill;
use AppBundle\Service\EmailService;
use AppBundle\Service\FileUploader;
use AppBundle\Service\SlugService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 * @Route("admin")
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdminController extends Controller
{

    /**
     * @Route("/profil/{slug}", name="profilAdmin")
     */
    public function profilAdminAction()
    {

        $numberUserByStatus['collaborator']['isActif'] = 0;
        $numberUserByStatus['collaborator']['isNotActif'] = 0;
        $numberUserByStatus['refCompany']['isActif'] = 0;
        $numberUserByStatus['refCompany']['isNotActif'] = 0;
        $numberUserByStatus['happyCoach']['isActif'] = 0;
        $numberUserByStatus['happyCoach']['isNotActif'] = 0;

        $em = $this->getDoctrine()->getManager();
        $nbProjectsByStatus = $em->getRepository('AppBundle:Project')->getNumberProjectsByStatus();
        $getNumberUserByRole = $em->getRepository('AppBundle:User')->getNumberUserByRole();
        foreach($getNumberUserByRole as $userByStatus) {
            switch($userByStatus['status']) {
                case (User::ROLE_EMPLOYE) :
                    if ($userByStatus['isActive'] === true) {
                        $numberUserByStatus['collaborator']['isActif'] = $userByStatus['nbUser'];
                    } else {
                        $numberUserByStatus['collaborator']['isNotActif'] = $userByStatus['nbUser'];
                    }
                    break;
                case (User::ROLE_COMPANY) :
                    if ($userByStatus['isActive'] === true) {
                        $numberUserByStatus['refCompany']['isActif'] = $userByStatus['nbUser'];
                    } else {
                        $numberUserByStatus['refCompany']['isNotActif'] = $userByStatus['nbUser'];
                    }
                    break;
                case (User::ROLE_HAPPYCOACH) :
                    if ($userByStatus['isActive'] === true) {
                        $numberUserByStatus['happyCoach']['isActif'] = $userByStatus['nbUser'];
                    } else {
                        $numberUserByStatus['happyCoach']['isNotActif'] = $userByStatus['nbUser'];
                    }
                    break;
            }
        }
        $nbCompany = $em->getRepository('AppBundle:Company')->getNumberCompany();
        $nbSkills = $em->getRepository('AppBundle:Skill')->getNumberSkill();
        return $this->render('pages/In/Admin/profilAdmin.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'nbProjectsByStatus' => $nbProjectsByStatus,
            'nbUserByStatus' => $numberUserByStatus,
            'nbCompany' => $nbCompany,
            'nbSkill' => $nbSkills
        ]);
    }

    /**
     * Lists all company entities.
     *
     * @Route("/listingCompany", name="listingCompany")
     * @Method("GET")
     */
    public function listingCompanyAction()
    {
        $em = $this->getDoctrine()->getManager();

        $companies = $em->getRepository('AppBundle:Company')->findAll();

        return $this->render('pages/In/Admin/company/index.html.twig', array(
            'companies' => $companies,
        ));

    }

    /**
     * Creates a new company entity.
     * @Route("/newCompany", name="newCompany")
     * @Method({"GET", "POST"})
     */
    public function newCompanyAction(Request $request, FileUploader $fileUploader, SlugService $slugService, EmailService $emailService)
    {
        $company = new Company();
        $form = $this->createForm('AppBundle\Form\CompanyType', $company);
        $form->remove('slug');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFile = $company->getFileUsers();
            $fileName = $fileUploader->upload($myFile, "csvFiles");
            $logo = $company->getLogo();
            $logoName = $fileUploader->upload($logo, "photoCompany");

            $company->setLogo($logoName);
            $company->setFileUsers($fileName);
            $company->setSlug($slugService->slugify($company->getName()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();
            $fileUsers = $fileUploader->transformCSV($fileUploader->getDirectory("csvFiles/") . $company->getFileUsers());
            unset($fileUsers[0]);
/*

            unlink($fileUploader->getDirectory("csvFiles") . '/' .$company->getFileUsers());

            $emailService->sendMailNewCompany($company, $this->container->getParameter('email_contact'), '1234');
            $countUser = $fileUploader->getCounter();
*/
            return $this->render('pages/In/Admin/company/recapNewCompany.html.twig', array(
                'fileUser' => $fileUsers,
                'company' => $company,
            ));

        }

        return $this->render('pages/In/Admin/company/new.html.twig', array(
            'company' => $company,
            'form' => $form->createView(),
        ));
    }

    /**
     * Deletes a company entity.
     *
     * @Route("/{slug}/deleteCompany", name="Company_delete")
     * @Method("DELETE")
     */
    public function deleteCompanyAction(Request $request, Company $company)
    {
        $form = $this->createDeleteFormCompany($company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($company);
            $em->flush();
        }

        return $this->redirectToRoute('listingCompany');
    }

    /**
     * Creates a form to delete a company entity.
     *
     * @param Company $company The company entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteFormCompany(Company $company)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('Company_delete', array('slug' => $company->getSlug())))
            ->setMethod('DELETE')
            ->getForm();
    }

    //User

    /**
     * Lists all user entities.
     *
     * @Route("/listingUser", name="listingUser")
     * @Method("GET")
     */
    public function listingUserAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->getUserByType('salary');

        return $this->render('pages/In/Admin/collaborators/index.html.twig', [
            'users' => $users,
            'listing' => 'Collaborateur',
            'status' => User::ROLE_EMPLOYE,
        ]);
    }

    /**
     * Lists all user HappyCoach.
     *
     * @Route("/listingHappyCoach", name="listingHappyCoach")
     * @Method("GET")
     */
    public function listingHappyCoachAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->getUserByType('happyCoach');
dump($users);
        return $this->render('pages/In/Admin/collaborators/index.html.twig', [
            'users' => $users,
            'listing' => 'HappyCoach',
            'status' => User::ROLE_HAPPYCOACH,
        ]);
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/newUser/{status}", name="newUser")
     * @Method({"GET", "POST"})
     */
    public function newUserAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, SlugService $slugService, EmailService $emailService, $status)
    {
        $user = new User();

        $form = $this->createForm('AppBundle\Form\NewUserType', $user);
        $form->remove('slug');
        if ($status == User::ROLE_HAPPYCOACH) {
            $form->remove('company')
                ->remove('status');
        }
        $form->handleRequest($request);
        //TODO Password and slugification
        if ($form->isSubmitted() && $form->isValid()) {
            $today = new \DateTime();
            $temp = $today->getTimestamp() - 1515703308;
            $password = $passwordEncoder->encodePassword($user, '1234');
            if($status == User::ROLE_HAPPYCOACH) {
                $user->setStatus(User::ROLE_HAPPYCOACH);
            }
            $user->setPassword($password);
            $user->setIsActive(0);
            $user->setSlug($slugService->slugify($user->getFirstName() . ' ' . $user->getLastName() . ' ' . $temp));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $emailService->sendMailNewUser($user, $this->container->getParameter('email_contact'), '1234');
            return $this->redirectToRoute('profilAdmin', array('slug' => $this->getUser()->getSlug()));
        }

        return $this->render('pages/In/Admin/collaborators/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
            'status' => $status,
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{slug}/deleteUser", name="User_delete")
     * @Method("DELETE")
     */
    public function deleteActionUser(Request $request, User $user)
    {
        $form = $this->createDeleteFormUser($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('listingUser');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteFormUser(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('User_delete', array('slug' => $user->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * Lists all project entities.
     *
     * @Route("/listingProjects/{status}", name="listingProjects")
     * @Method("GET")
     */
    public function indexAction($status)
    {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('AppBundle:Project')->getProjectsByStatus($status);

        return $this->render('pages/In/Admin/projects/index.html.twig', array(
            'projects' => $projects,
        ));
    }

}