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


class CompanyController extends Controller
{

    /**
     * @Route("/company/account", name="companyAccount_home")
     */
    public function companyAccountAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_RECRUITER', null, 'Unable to access this page!');

        $user = $this->getUser();

        return $this->redirectToRoute('jobRecruiter_view',array('id' => $user->getId()));
    }

    /**
     * @Route("/seeker/account", name="seekerAccount_home")
     */
    public function seekerAccountAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_SEEKER', null, 'Unable to access this page!');
        // replace this example code with whatever you need
        $user = $this->getUser();

        return $this->redirectToRoute('jobSeeker_view',array('id' => $user->getId()));
    }


}
