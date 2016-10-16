<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Passenger;
use Symfony\Component\Validator\Constraints\DateTime;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class PassengerController extends Controller
{
    /**
     * @Route("/passenger/", name="passenger_home")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->redirectToRoute('passenger_viewAll');
    }


    /**
     * @Route("/passenger/create", name="passenger_create")
     */
    public function createAction(Request $request)
    {
        $passenger = new Passenger();

        $form = $this->createFormBuilder($passenger)
            ->add('name', TextType::class)
            ->add('contactNo', IntegerType::class)
            ->add('address', EmailType::class)
            ->add('emailAddress', TextType::class)
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
     
            $em = $this->getDoctrine()->getManager();

            $em->persist($passenger);
            $em->flush();

            return $this->redirectToRoute("passenger_home");
        }

       

        // replace this example code with whatever you need
        return $this->render('passenger/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/passenger/view/{id}", name="passenger_view")
     */
    public function viewAction($id, Request $request)
    {
      
        $passenger = $this->getDoctrine()
            ->getRepository('AppBundle:Passenger')
            ->find($id);

        if (!$passenger) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('passenger/view.html.twig', array('passenger'=> $passenger));  

    }

    /**
     * @Route("/passenger/view", name="passenger_viewAll")
     */
    public function viewallAction( Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Passenger');
        $passengers = $repository->findAll();

        return $this->render('passenger/viewall.html.twig', array('passengers' => $passengers));
    }
    
    /**
     * @Route("/passenger/update/{id}", name="passenger_update")
     */
    public function updateAction( $id,Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $passenger = $em->getRepository('AppBundle:Passenger')->find($id);

        $form = $this->createFormBuilder($jobSeeker)
            ->add('name', TextType::class)
            ->add('expectedSalary', IntegerType::class)
            ->add('emailAddress', EmailType::class)
            ->add('linkedInURL', UrlType::class)
            ->add('jobSeekerField',ChoiceType::class, array(
                'choices'  => $fieldIds,
                'choices_as_values' => true,
                'label'=>'Preferred Field'
            ))
            ->add('save', SubmitType::class, array('label' => 'Update Account'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ... perform some action, such as saving the task to the database
            $em->flush();

            return $this->redirectToRoute('jobSeeker_viewAll');
        }

        // replace this example code with whatever you need
        return $this->render('jobSeeker/update.html.twig', array('form' => $form->createView()));

    }

    /**
     * @Route("/jobseeker/linkedin/{id}", name="jobSeeker_linkedin")
     */
    public function linkedInAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $jobRecruiter = $em->getRepository('AppBundle:JobSeeker')->find($id);

        $url = $jobRecruiter->getLinkedInURL();

        return $this->redirect($url);
    }

    /**
     * @Route("/jobseeker/delete/{id}", name="jobseeker_delete")
     */
    public function deleteAction($id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $jobSeeker = $em->getRepository('AppBundle:JobSeeker')->find($id);

        $em->remove($jobSeeker);
        $em->flush();

        return $this->redirectToRoute('admin_viewSeekers');
    }

 
}
