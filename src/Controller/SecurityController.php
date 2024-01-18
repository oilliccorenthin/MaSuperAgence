<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController{


    public function login(AuthenticationUtils $authenticationUtils)
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/login.html.twig',[
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
}

?>
