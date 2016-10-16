<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Officer;
use Symfony\Component\Validator\Constraints\DateTime;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class OfficerController extends Controller
{
    /**
     * @Route("/officer/", name="officer_home")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
       return $this->redirectToRoute('officer_viewAll');
    }


    /**
     * @Route("/officer/create", name="officer_create")
     */
    public function createAction(Request $request)
    {
        $officer = new Officer();

        $form = $this->createFormBuilder($officer)
            ->add('name', TextType::class)
            ->add('address', TextType::class)
            ->add('contactNo', TextType::class)
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
     
            $em = $this->getDoctrine()->getManager();

            $em->persist($officer);
            $em->flush();

            return $this->redirectToRoute("officer_home");
        }

        // replace this example code with whatever you need
        return $this->render('officer/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/officer/view/{id}", name="officer_view")
     */
    public function viewAction($id, Request $request)
    {
        
        $officer = $this->getDoctrine()
            ->getRepository('AppBundle:Officer')
            ->find($id);

        if (!$officer) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('officer/view.html.twig', array('officer'=> $officer));  

    }

    /**
     * @Route("/officer/view", name="officer_viewAll")
     */
    public function viewallAction( Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Officer');
        $officers = $repository->findAll();

        return $this->render('officer/viewall.html.twig', array('officers' => $officers));

    }

    /**
     * @Route("/officer/update/{id}", name="officer_update")
     */
    public function updateAction( $id,Request $request)
    {
       
        $em = $this->getDoctrine()->getManager();
        $officer = $em->getRepository('AppBundle:Officer')->find($id);

        $form = $this->createFormBuilder($officer)
            ->add('name', TextType::class)
            ->add('address', TextType::class)
            ->add('contactNo', TextType::class)
            ->add('emailAddress', EmailType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Account'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ... perform some action, such as saving the task to the database
            $em->flush();

            return $this->redirectToRoute('officer_viewAll');
        }

        // replace this example code with whatever you need
        return $this->render('officer/update.html.twig', array('form' => $form->createView()));
    }


    /**
     * @Route("/officer/delete/{id}", name="officer_delete")
     */
    public function deleteAction($id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $officer = $em->getRepository('AppBundle:Officer')->find($id);

        $em->remove($officer);
        $em->flush();

        return $this->redirectToRoute('admin_viewOfficers');
    }



 
}
