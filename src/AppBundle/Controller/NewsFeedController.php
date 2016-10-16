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


class NewsFeedController extends Controller
{
    /**
     * @Route("/newsseeker/", name="newsSeeker_home")
     */
    public function newsFeedSeekerAction(Request $request)
    {

        $user = $this->getUser();
        $userField = $user->getJobSeekerField()->getName();
        $salary = $user->getExpectedSalary();

        $query = $this->getDoctrine()->getManager()->createQuery("SELECT v FROM AppBundle\Entity\Vacancy v JOIN v.vacancyField f WHERE f.name = :name AND v.salaryGiven BETWEEN :value1 AND :value2");


        $query->setParameters(array(
            'name' => $userField,
            'value1' => $salary -10000,
            'value2' => $salary +10000,
        ));

        $newsfeeds = $query->getResult();


        return $this->render('usecases/newsseeker_home.html.twig', array('newsfeeds' => $newsfeeds));
    }

    /**
     * @Route("/newsrecruiter/", name="newsRecruiter_home")
     */
    public function newsFeedRecruiterAction(Request $request)
    {

        $userId = $this->getUser()->getId();
       /* $userField = $user->getJobSeekerField()->getName();
        $salary = $user->getExpectedSalary();*/

        $query = $this->getDoctrine()->getManager()->createQuery("SELECT v FROM AppBundle\Entity\JobSeekerAppliedVacancy v JOIN v.jobSeekerVacancy j JOIN j.postedVacancy p WHERE p.id = :value ");
        $query->setParameter('value', $userId);

        $newsfeeds = $query->getResult();


        return $this->render('usecases/newsrecruiter_home.html.twig', array('newsfeeds' => $newsfeeds));
    }

    /**
     * @Route("/aboutus/", name="aboutUs")
     */
    public function aboutUsAction(Request $request)
    {

        return $this->render('usecases/about.html.twig');
    }

}