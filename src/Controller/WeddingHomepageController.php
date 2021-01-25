<?php

declare(strict_types=1);

namespace App\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class WeddingHomepageController extends AbstractController
{
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('wedding');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('access_page.html.twig', [
            'error' => $error
        ]);
    }

    /**
     * @throws LogicException
     */
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    public function wedding(): Response
    {
        return $this->render('wedding/homepage.html.twig');
    }
}