<?php

namespace HB\HyperbricoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MagasinController extends Controller
{
    public function indexAction()
    {
        return $this->render('HBHyperbricoBundle:Magasin:index.html.twig');
    }

    public function voirPlanPapeeteAction($format)
    {
    	$response = new Response;
        $response->setContent(file_get_contents(__DIR__.'/../../../../web/img/plans/hyperbrico_papeete_plan.'.$format));
        $response->headers->set('Content-Type', ($format == 'pdf' ? 'application' : 'image').'/'.$format);
        return $response;
    }
}
