<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Vacancy;
use AppBundle\Entity\JobSeeker;
use AppBundle\Entity\JobSeekerAppliedVacancy;
use Symfony\Component\HttpFoundation\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ApplyController extends Controller
{
    /**
     * @Route("/apply/{id}", name="apply_vacancy")
     */
    public function applyAction($id, Request $request)
    {
        $appliedVacancy = new JobSeekerAppliedVacancy(); 

        $vacancy = $this->getDoctrine()
            ->getRepository('AppBundle:Vacancy')
            ->find($id); 

        $seeker = $this->getUser();

        $form = $this->createFormBuilder($appliedVacancy)
           
            ->add('description',TextType::class)
            ->add('cV', FileType::class, array('label' => ' Upload CV'))
            ->add('save', SubmitType::class, array('label' => 'Apply'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            // ... perform some action, such as saving the task to the database
            $appliedVacancy->setJobSeekerAppliedVacancy($seeker);
            $appliedVacancy->setJobSeekerVacancy($vacancy);
     
            $em = $this->getDoctrine()->getManager();

            $appliedVacancy->upload();

            // ... perform some action, such as saving the task to the database
            $em->persist($appliedVacancy);
            $em->flush();

            return $this->redirectToRoute('vacancy_view',array('id'=>$id));
        }

        // replace this example code with whatever you need
       return $this->render('usecases/apply_home.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/apply/view/{id}", name="apply_view")
     */
    public function viewAction($id, Request $request)
    {
        
        $appliedVacancy = $this->getDoctrine()
            ->getRepository('AppBundle:JobSeekerAppliedVacancy')
            ->find($id);

        if (!$appliedVacancy) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('apply/view.html.twig', array('appliedvacancy'=> $appliedVacancy));
    }

    /**
     * @Route("/apply/viewall/{id}", name="apply_viewAll")
     */
    public function viewAllAction($id, Request $request)
    {


        $appliedVacancies = $this->getDoctrine()
            ->getRepository('AppBundle:JobSeekerAppliedVacancy')
            ->findBy( array('jobSeekerVacancy' => $id));

       /* $appliedVacancies = $this->getDoctrine()
            ->getRepository('AppBundle:JobSeekerAppliedVacancy')
            ->findAll();

        $relatedVacancies = $appliedVacancies->getCategory()->getName();*/


        return $this->render('apply/viewall.html.twig', array('appliedvacancies'=> $appliedVacancies));
    }

    /**
     * @Route("/aply/view", name="apply_viewAllAll")
     */
    public function viewAllAllAction(Request $request)
    {

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:JobSeekerAppliedVacancy');

        $appliedVacancies = $repository->findAll();

        return $this->render('apply/viewall.html.twig', array('appliedvacancies'=> $appliedVacancies));
    }

    /**
     * @Route("/apply/delete/{id}", name="apply_delete")
     */
    public function deleteAction($id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository('AppBundle:JobSeekerAppliedVacancy')->find($id);

        $em->remove($vacancy);
        $em->flush();

        return $this->redirectToRoute('admin_viewApplications');
    }


    /**
     * Serves an uploaded file.
     *
     * @Route("/{id}/file", name="event_file")
     *
     */
    public function fileViewAction($id)
    {
        $appliedVacancy = $this->getDoctrine()
            ->getRepository('AppBundle:JobSeekerAppliedVacancy')
            ->find($id);

        $abPath = $appliedVacancy->getAbsolutePath();

        return new BinaryFileResponse($abPath);
    }
}