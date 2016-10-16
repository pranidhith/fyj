<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Advertisement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Validator\Constraints\DateTime;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class AdvertisementController extends Controller
{
    /**
     * @Route("/advertisement/", name="advertisement_home")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->redirectToRoute('advertisement_viewAll');
    }


    /**
     * @Route("/advertisement/create", name="advertisement_create")
     */
    public function createAction(Request $request)
    {
            $advertisement = new Advertisement();
            $submitter = $this->getUser();

            $form = $this->createFormBuilder($advertisement)
                ->add('sizeX', IntegerType::class)
                ->add('sizeY', IntegerType::class)
                ->add('daysToDisplay', IntegerType::class)
                ->add('image', FileType::class, array('label' => ' Upload Image'))
                ->add('save', SubmitType::class, array('label' => 'Submit Advertisement'))
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // ... perform some action, such as saving the task to the database
                $advertisement->setSubmittedAd($submitter);
                $em = $this->getDoctrine()->getManager();

                $advertisement->upload();

                $em->persist($advertisement);
                $em->flush();

                return $this->redirectToRoute("homepage");
            }

            // replace this example code with whatever you need
            return $this->render('advertisement/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/advertisement/view/{id}", name="advertisement_view")
     */
    public function viewAction($id, Request $request)
    {
        //$jobSeeker =  JobSeeker::getOne($id);

        $advertisement = $this->getDoctrine()
            ->getRepository('AppBundle:Advertisement')
            ->find($id);

        if (!$advertisement) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('advertisement/view.html.twig', array('advertisement'=> $advertisement));

    }

    /**
     * @Route("/advertisement/view", name="advertisement_viewAll")
     */
    public function viewallAction( Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Advertisement');
        $advertisements = $repository->findAll();

        return $this->render('advertisement/viewall.html.twig', array('advertisements' => $advertisements));
    }

    /**
     * @Route("/advertisement/update/{id}", name="advertisement_update")
     */
    public function updateAction( $id,Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $advertisement = $em->getRepository('AppBundle:Advertisement')->find($id);

        $form = $this->createFormBuilder($advertisement)
            ->add('sizeX', IntegerType::class)
            ->add('sizeY', IntegerType::class)
            ->add('daysToDisplay', IntegerType::class)
            ->add('image', FileType::class, array('label' => ' Upload Image'))
            ->add('save', SubmitType::class, array('label' => 'Update Account'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $advertisement->upload();
            // ... perform some action, such as saving the task to the database
            $em->flush();

            return $this->redirectToRoute('advertisement_viewAll');
        }

        // replace this example code with whatever you need
        return $this->render('advertisement/update.html.twig', array('form' => $form->createView()));

    }

    /**
     * @Route("/advertisement/delete/{id}", name="advertisement_delete")
     */
    public function deleteAction($id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $advertisement = $em->getRepository('AppBundle:Advertisement')->find($id);

        $em->remove($advertisement);
        $em->flush();

        return $this->redirectToRoute('admin_viewAdvertisements');
    }


    /**
     * Serves an uploaded file.
     *
     * @Route("/{id}/image", name="advertisement_file")
     *
     */
    public function adViewAction($id)
    {
        $ad = $this->getDoctrine()
            ->getRepository('AppBundle:Advertisement')
            ->find($id);

        $abPath = $ad->getAbsolutePath();

        return new BinaryFileResponse($abPath);
    }

}
