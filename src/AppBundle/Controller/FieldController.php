<?php

namespace AppBundle\Controller;

//use Proxies\__CG__\AppBundle\Entity\Field;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Field;
use Symfony\Component\Validator\Constraints\DateTime;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class FieldController extends Controller
{
    /**
     * @Route("/field/", name="field_home")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->redirectToRoute('field_viewAll');
    }


    /**
     * @Route("/field/create", name="field_create")
     */
    public function createAction(Request $request)
    {
        $field = new Field();


        $form = $this->createFormBuilder($field)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create A New Field'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            // ... perform some action, such as saving the task to the database

            $em = $this->getDoctrine()->getManager();

            $em->persist($field);
            $em->flush();

            return $this->redirectToRoute("vacancy_create");
        }



        // replace this example code with whatever you need
        return $this->render('field/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/field/view/{id}", name="field_view")
     */
    public function viewAction($id, Request $request)
    {

        $field = $this->getDoctrine()
            ->getRepository('AppBundle:Field')
            ->find($id);

        if (!$field) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('field/view.html.twig', array('field'=> $field));

    }

    /**
     * @Route("/field/view", name="field_viewAll")
     */
    public function viewallAction( Request $request)
    {

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Field');
        $fields = $repository->findAll();

        return $this->render('field/viewall.html.twig', array('fields' => $fields));
    }

    /**
     * @Route("/field/update/{id}", name="field_update")
     */
    public function updateAction( $id,Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $field = $em->getRepository('AppBundle:Field')->find($id);

        $form = $this->createFormBuilder($field)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Update Field'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ... perform some action, such as saving the task to the database
            $em->flush();

            return $this->redirectToRoute('field_viewAll');
        }

        // replace this example code with whatever you need
        return $this->render('field/update.html.twig', array('form' => $form->createView()));

    }


    /**
     * @Route("/field/delete/{id}", name="field_delete")
     */
    public function deleteAction($id, Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $field = $em->getRepository('AppBundle:Field')->find($id);

        $em->remove($field);
        $em->flush();

        return $this->redirectToRoute('field_viewAll');
    }


}
