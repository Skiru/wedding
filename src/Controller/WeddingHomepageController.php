<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class WeddingHomepageController extends AbstractController
{
    public function homepage(): Response
    {
        return $this->render('wedding/homepage.html.twig');
    }
}