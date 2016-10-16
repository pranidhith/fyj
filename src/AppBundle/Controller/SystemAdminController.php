<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SystemAdmin;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\JobRecruiter;
use Symfony\Component\Validator\Constraints\DateTime;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class SystemAdminController extends Controller
{
    /**
     * @Route("/admin/", name="admin_home")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->redirectToRoute('homepage');
    }


    /**
     * @Route("/admin/create", name="admin_create")
     */
    public function createAction(Request $request)
    {
        $admin = new SystemAdmin();

        $form = $this->createFormBuilder($admin)
            ->add('name', TextType::class)
            ->add('contactNumber', TextType::class)
            ->add('emailAddress', EmailType::class)
            ->add('username', TextType::class)
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('save', SubmitType::class, array('label' => 'Create Account'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            // ... perform some action, such as saving the task to the database

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($admin);

            //Encode the password
            $encodedPassword = password_hash($admin->getPassword(),PASSWORD_BCRYPT);
            $admin->setPassword($encodedPassword);

            $admin->setIsActive(true);

            //--- save
            $em = $this->getDoctrine()->getManager();

            $em->persist($admin);
            $em->flush();

            return $this->redirectToRoute('task_success');
        }

        // replace this example code with whatever you need
        return $this->render('admin/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/admin/view", name="admin_view")
     */
    public function viewAction(Request $request)
    {
        $id = $this ->getUser()->getId();
        $admin = $this->getDoctrine()
            ->getRepository('AppBundle:SystemAdmin')
            ->find($id);

        if (!$admin) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('admin/view.html.twig', array('admin'=> $admin));

    }

    /**
     * @Route("/admin/update/{id}", name="admin_update")
     */
    public function updateAction( $id,Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $admin = $em->getRepository('AppBundle:SystemAdmin')->find($id);

        $form = $this->createFormBuilder($admin)
            ->add('name', TextType::class)
            ->add('contactNumber', TextType::class)
            ->add('emailAddress', EmailType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Account'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ... perform some action, such as saving the task to the database
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        // replace this example code with whatever you need
        return $this->render('admin/update.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/admin/delete/{id}", name="admin_delete")
     */
    public function deleteAction($id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $admin = $em->getRepository('AppBundle:SystemAdmin')->find($id);

        $em->remove($admin);
        $em->flush();
    }

    /**
     * @Route("/admin/viewrecruiters", name="admin_viewRecruiters")
     */
    public function viewRecruitersAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        return $this->redirectToRoute('jobRecruiter_viewAll');

    }

    /**
     * @Route("/admin/viewseekers", name="admin_viewSeekers")
     */
    public function viewSeekersAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        return $this->redirectToRoute('jobSeeker_viewAll');

    }

    /**
     * @Route("/admin/viewvacancies", name="admin_viewVacancies")
     */
    public function viewVacanciesAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        return $this->redirectToRoute('vacancy_viewAll');

    }

    /**
     * @Route("/admin/viewapplications", name="admin_viewApplications")
     */
    public function viewApplicationsAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        return $this->redirectToRoute('apply_viewAllAll');

    }

    /**
     * @Route("/admin/viewadvertisements", name="admin_viewAdvertisements")
     */
    public function viewAdvertisementsAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        return $this->redirectToRoute('advertisement_viewAll');

    }

    /**
     * @Route("/admin/viewfields", name="admin_viewFields")
     */
    public function viewFieldsAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        return $this->redirectToRoute('field_viewAll');

    }

}
