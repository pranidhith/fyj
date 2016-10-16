<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\JobSeeker;
use AppBundle\Entity\JobRecruiter;


class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login_route")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'security/login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
        /*$user = $this->getUser();
        $objClass = $user->get_class();

        //if($objClass == "JobRecruiter"){

            $recruiter = $this->getDoctrine()
                ->getRepository('AppBundle:JobRecruiter')
                ->find($user->getId());

            $recruiter->setaccLastAccessedDate(new \DateTime('now'));

            $recruiter->flush();*/
        //}

        // this controller will not be executed,
        // as the route is handled by the Security system
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }

//---------------------------------------------------recruiter-----------------------------


    /**
     * @Route("/recruiter/login", name="loginRec_route")
     */
    public function loginRecAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'security/login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    /**
     * @Route("/recruiter/login_check", name="loginRec_check")
     */
    public function loginCheckRecAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
        //return $this->redirectToRoute('task_success');
    }

    /**
     * @Route("login/redirect", name="redirect")
     */
    public function redirectAction()
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $role = $user->getRoles();
        $userId = $user->getId();

        if ($role == 'ROLE_SEEKER'){

            $jobSeeker = $this->getDoctrine()
                ->getRepository('AppBundle:JobSeeker')
                ->find($userId);

            $user->setAccLastAccessedDate(new \DateTime('now'));

            $em->persist($jobSeeker);
            $em->flush();

            $this -> get('session')->set('accLastAccessedDate', new \DateTime('now'));

            return $this->redirectToRoute('jobSeeker_home');
        }
        if ($role == 'ROLE_RECRUITER'){

            $jobRecruiter = $this->getDoctrine()->getManager()
                ->getRepository('AppBundle:JobRecruiter')
                ->find($userId);

            $user->setAccLastAccessedDate(new \DateTime('now'));

            $em->persist($jobRecruiter);
            $em->flush();

            return $this->redirectToRoute('jobRecruiter_home');
        }
        if ($role == 'ROLE_ADMIN'){
            return $this->redirectToRoute('admin_home');
        }

    }


    /**
     * @Route("/recruiter/logout", name="logoutRec")
     */
    public function logoutRecAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
        //return $this->redirectToRoute('task_success');
    }



//    Signup


    /**
     * @Route("/signup/seeker", name="signup_seeker")
     */
    public function signupSeekerAction(Request $request)
    {

        $jobSeeker = new JobSeeker();

        $fields = $this->getDoctrine()
            ->getRepository('AppBundle:Field')
            ->findAll();

        $fieldIds = array();

        foreach ($fields as $field) {
            $fieldIds[$field->getName()] = $field;
        }


        $jobSeeker->setAccCreatedDate(new \DateTime('now'));
        $jobSeeker->setaccLastAccessedDate(new \DateTime('now'));

        $form = $this->createFormBuilder($jobSeeker)
            ->add('name', TextType::class)
            ->add('expectedSalary', IntegerType::class)
            ->add('emailAddress', EmailType::class)
            ->add('contactNo', TextType::class)
            ->add('linkedInURL', UrlType::class)
            ->add('jobSeekerField',ChoiceType::class, array(
                'choices'  => $fieldIds,
                'choices_as_values' => true,
                'label'=>'Preferred Field'
            ))
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

        if ($form->isSubmitted() && $form->isValid()) {
            // ... perform some action, such as saving the task to the database

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($jobSeeker);

            //Encode the password
            $encodedPassword = password_hash($jobSeeker->getPassword(),PASSWORD_BCRYPT);
            $jobSeeker->setPassword($encodedPassword);

            $jobSeeker->setIsActive(true);

            //--- save
            $em = $this->getDoctrine()->getManager();

            $em->persist($jobSeeker);
            $em->flush();

            //return $this->redirectToRoute("jobSeeker_home");

            return $this->redirectToRoute('task_success');
        }


        //$error = $jobSeeker->getError(); //Set a valid error


        // replace this example code with whatever you need
        return $this->render('security/signup.html.twig', array('form' => $form->createView()));//,'error'         => $error,));
    }

    /**
     * @Route("/signup/recruiter", name="signup_recruiter")
     */
    public function signupRecruiterAction(Request $request)
    {

        $jobRecruiter = new JobRecruiter();

        $jobRecruiter->setAccCreatedDate(new \DateTime('now'));
        $jobRecruiter->setaccLastAccessedDate(new \DateTime('now'));
        $jobRecruiter->setLatitude(21241.323);
        $jobRecruiter->setLongitude(35235.325);

        $form = $this->createFormBuilder($jobRecruiter)
            ->add('name', TextType::class)
            ->add('companyName', TextType::class)
            ->add('companyAddress', TextType::class)
            ->add('emailAddress', EmailType::class)
            ->add('contactNo', TextType::class)
            ->add('linkedInURL', UrlType::class)
            ->add('username', TextType::class)
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('save', SubmitType::class, array('label' => 'Sign Up'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ... perform some action, such as saving the task to the database

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($jobRecruiter);

            //Encode the password
            $encodedPassword = password_hash($jobRecruiter->getPassword(),PASSWORD_BCRYPT);
            $jobRecruiter->setPassword($encodedPassword);

            $jobRecruiter->setIsActive(true);

            //--- save
            $em = $this->getDoctrine()->getManager();

            $em->persist($jobRecruiter);
            $em->flush();

            //return $this->redirectToRoute("jobSeeker_home");

            return $this->redirectToRoute('task_success');
        }


        //$error = $jobSeeker->getError(); //Set a valid error


        // replace this example code with whatever you need
        return $this->render('security/signup.html.twig', array('form' => $form->createView()));//,'error'         => $error,));
    }
}