<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function indexAction()
    {

        $listAdverts = array(
            array(
                'title'   => 'Recherche développpeur Symfony',
                'id'      => 1,
                'author'  => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
                'date'    => new \Datetime()),

            array(
                'title'   => 'Mission dév Drupal 8',
                'id'      => 2,
                'author'  => 'Jeanne',
                'content' => 'Une super boîte (Drup18) renforce ses équipes en compétence Drupal - Paris. Rejoignez-les…',
                'date'    => new \Datetime()),

            array(
                'title'   => 'Recherche urgente débutant PHP/Symfony 3/4',
                'id'      => 3,
                'author'  => 'Fabien Potencier',
                'content' => 'Sensiolabs manque de grands débutants Symfony ! Incroyable mais vrai ;)…',
                'date'    => new \Datetime()),

            array(

                'title'   => 'Mission de webmaster',
                'id'      => 4,
                'author'  => 'Hugo',
                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                'date'    => new \Datetime()),

            array(

                'title'   => 'Offre de stage webdesigner',
                'id'      => 5,
                'author'  => 'Mathieu',
                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
                'date'    => new \Datetime())
        );
        return $this->render('@Core/index.html.twig', array(
            'listAdverts' => $listAdverts
        ));
    }

    public function contactAction(Request $request)
    {
        $session = $request->getSession();
        $session->getFlashBag()->add('info', 'la page de contact n\'est pas encore disponible, 
        merci de revenir plus tard');

        return $this->redirectToRoute('core_homepage');
    }
}
