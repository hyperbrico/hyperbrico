<?php

namespace HB\HyperbricoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConseilController extends Controller
{
    public function indexAction()
    {
        return $this->render('HBHyperbricoBundle:Conseil:index.html.twig');
    }

}
