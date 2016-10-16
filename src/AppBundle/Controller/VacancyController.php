<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Vacancy;
use AppBundle\Entity\JobRecruiter;
use Symfony\Component\Validator\Constraints\DateTime;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class VacancyController extends Controller
{
    /**
     * @Route("/vacancy/", name="vacancy_home")
     */
    public function indexAction(Request $request)
    {
        
        //$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        // replace this example code with whatever you need
       return $this->redirectToRoute('vacancy_create');
    }


    /**
     * @Route("/vacancy/create", name="vacancy_create")
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_RECRUITER', null, 'Unable to access this page!');

        $vacancy = new Vacancy();

        $recruiter = $this->getUser();

        $fields = $this->getDoctrine()
            ->getRepository('AppBundle:Field')
            ->findAll();

        $fieldIds = array();

        foreach ($fields as $field) {
            $fieldIds[$field->getName()] = $field;
        }

        $vacancy->setLatitude(21241.323);
        $vacancy->setLongitude(35235.325);

//$product->setCategory($category);

        $form = $this->createFormBuilder($vacancy)
            ->add('place', TextType::class)
            ->add('position', TextType::class)
            ->add('salaryGiven', IntegerType::class)
            ->add('closingDate', DateType::class)
            ->add('vacancyField',ChoiceType::class, array(
                'choices'  => $fieldIds,
                'choices_as_values' => true,
                'label'=>'Preferred Field'
            ))

            ->add('save', SubmitType::class, array('label' => 'Create Vacancy'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {

            $vacancy->setPostedVacancy($recruiter);
            // ... perform some action, such as saving the task to the database
     
            $em = $this->getDoctrine()->getManager();

            $em->persist($vacancy);
            $em->flush();


            return $this->redirectToRoute("vacancy_viewCompany");
        }

        // replace this example code with whatever you need
        return $this->render('vacancy/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/vacancy/view/{id}", name="vacancy_view")
     */
    public function viewAction($id, Request $request)
    {
        
        $vacancy = $this->getDoctrine()
            ->getRepository('AppBundle:Vacancy')
            ->find($id);

        if (!$vacancy) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('vacancy/view.html.twig', array('vacancy'=> $vacancy));  

    }

    /**
     * @Route("/vacancy/view", name="vacancy_viewAll")
     */
    public function viewallAction( Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Vacancy');
        $vacancies = $repository->findAll();

        return $this->render('vacancy/viewall.html.twig', array('vacancies' => $vacancies));

    }

    /**
     * @Route("/vacancy/viewcompany", name="vacancy_viewCompany")
     */
    public function viewallCompanyAction( Request $request)
    {
        $userID = $this->getUser()->getId();

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Vacancy');

        //$vacancies = $repository->findById($userID);

        $query = $this->getDoctrine()->getManager()->createQuery("SELECT v FROM AppBundle\Entity\Vacancy v JOIN v.postedVacancy p WHERE p.id = :value");
        $query->setParameter('value', $userID);
        $vacancies = $query->getResult();

        return $this->render('vacancy/viewcompany.html.twig', array('vacancies' => $vacancies));

    }

    /**
     * @Route("/vacancy/update/{id}", name="vacancy_update")
     */
    public function updateAction( $id,Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_RECRUITER', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository('AppBundle:Vacancy')->find($id);

        //$vacancy = new Vacancy();

        $recruiters = $this->getDoctrine()
        ->getRepository('AppBundle:JobRecruiter')
        ->findAll();

        $recruiterIds = array();
        
        foreach ($recruiters as $recruiter) {
            $recruiterIds[$recruiter->getName()] = $recruiter; 
        }

        //$vacancy->setLatitude(21241.323);
        //$vacancy->setLongitude(35235.325);

//$product->setCategory($category);

        $form = $this->createFormBuilder($vacancy)
            ->add('place', TextType::class)
            ->add('salaryGiven', IntegerType::class)
            ->add('closingDate', DateType::class)


            ->add('postedVacancy',ChoiceType::class, array(
            'choices'  => $recruiterIds,
            'choices_as_values' => true,
            'label'=>'Company'
                ))

            ->add('save', SubmitType::class, array('label' => 'Create Vacancy'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ... perform some action, such as saving the task to the database
            $em->flush();

            return $this->redirectToRoute('vacancy_viewAll');
        }

        // replace this example code with whatever you need
        return $this->render('vacancy/update.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/vacancy/delete/{id}", name="vacancy_delete")
     */
    public function deleteAction($id, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository('AppBundle:Vacancy')->find($id);

        $em->remove($vacancy);
        $em->flush();

        return $this->redirectToRoute('admin_viewVacancies');
    }

    /**
     * @Route("/vacancy/delete/", name="vacancy_deleteAll")
     */
    public function deleteAllAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository('AppBundle:Vacancy');

        $em->remove($vacancy);
        $em->flush();
    }
 
}
