<?php

namespace HB\HyperbricoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class QuiSommesNousController extends Controller
{
    public function indexAction()
    {
        return $this->render('HBHyperbricoBundle:QuiSommesNous:index.html.twig');
    }

}
