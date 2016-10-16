<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\Model\ChangePassword;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\JobSeeker;

class PasswordController extends Controller
{

    /**
     * @Route("/password/update", name="password_update")
     */
    public function changePasswdAction(Request $request)
    {
        $changePasswordModel = new ChangePassword();
        $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // perform some action,
            // such as encoding with MessageDigestPasswordEncoder and persist`
            $user = $this->getUser();
            //$factory = $this->get('security.encoder_factory');
            //$encoder = $factory->getEncoder();

            //Encode the password
            $encodedPassword = password_hash($changePasswordModel->newPassword, PASSWORD_BCRYPT);
            $user->setPassword($encodedPassword);

            $user->setIsActive(true);

            //--- save
            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('task_success'));
        }

        return $this->render(':usecases:change_password.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}