<?php

namespace HB\HyperbricoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ServicesController extends Controller
{
    public function indexAction()
    {
        return $this->render('HBHyperbricoBundle:Services:index.html.twig');
    }

}
