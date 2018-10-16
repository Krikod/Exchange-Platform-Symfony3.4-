<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdvertController extends Controller
{

    public function indexAction($page)
    {
        if ($page < 1 ) {
            throw new NotFoundHttpException('Page "' .$page.'" inexitante');
        }
//        $content = $this
//            ->container
//            ->get('templating')
//            ->render('AppBundle:Advert:index.html.twig', array('nom' => 'winzou'));
//        return new Response($content);

        $listAdverts = array(

            array(

                'title'   => 'Recherche développpeur Symfony',

                'id'      => 1,

                'author'  => 'Alexandre',

                'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',

                'date'    => new \Datetime()),

            array(

                'title'   => 'Mission de webmaster',

                'id'      => 2,

                'author'  => 'Hugo',

                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',

                'date'    => new \Datetime()),

            array(

                'title'   => 'Offre de stage webdesigner',

                'id'      => 3,

                'author'  => 'Mathieu',

                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',

                'date'    => new \Datetime())

        );

        return $this->render('AppBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts
        ));
    }

//    public function viewAction($id, Request $request)
//    {
////        $url = $this->generateUrl(
////            'platform_view', array('id' => 5),
////            UrlGeneratorInterface::ABSOLUTE_URL
////        );
////        return new Response("L'url de l'annnonce est : " .$url);
//        $tag = $request->query->get('tag');
//        return new Response("L'id est " .$id. " et le tag est " .$tag);
//    }
public function viewAction($id)
{
//        $tag = $request->query->get('tag');
    $advert = array(

        'title'   => 'Recherche développpeur Symfony2',

        'id'      => $id,

        'author'  => 'Alexandre',

        'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',

        'date'    => new \Datetime()

    );
        return $this->render(
            'AppBundle:Advert:view.html.twig',
            array('advert' => $advert)
        );

//    $url = $this->generateUrl('platform_home');
//    return $this->redirectToRoute('platform_home');

//    $response = new Response(json_encode(array('id' => $id)));
//    $response->headers->set('Content-Type', 'application/json');
//    return $response;

//    return new JsonResponse(array('id' => $id));

//    $session = $request->getSession();
//    $userId = $session->get('user_id');
//    // On définit une nouvelle valeur pour cette variable user_id
//    $session->set('user_id', 91);
//    // On n'oublie pas de renvoyer une réponse
//    return new Response("<body>Je suis une page de test, je n'ai rien à dire</body>");

}

    public function addAction(Request $request)
    {   // UTILISATION D'UN SERViCE ANTISPAM:
        // On récupère le service
        $antispam = $this->container->get('app.antispam');

        // $text contient le texte d'un message quelconque
        $text = 'Texte de moins de 50 caractères --> spam';
        if ($antispam->isSpam($text)) {
            throw new \Exception('Votre message a été détecté comme spam !');
        }

        // Ici message n'est pas un spam

        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag->add('notice', 'Annonce bien enregistrée');
            return $this->redirectToRoute('platform_view', array('id' =>5));
        }

        return $this->render('AppBundle:Advert:add.html.twig');

//        $session = $request->getSession();
//        $session->getFlashBag()->add('info', 'Annonce bien enregistrée');
//        $session->getFlashBag()->add('info', 'Oui, oui elle es enregistrée!');

    }

    public function editAction($id, Request $request)
    {
//        Récupérer annonce

        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée');
            return $this->redirectToRoute('platform_view', array('id' =>$id));
        }
        $advert = array(

            'title'   => 'Recherche développpeur Symfony',

            'id'      => $id,

            'author'  => 'Alexandre',

            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',

            'date'    => new \Datetime()

        );
        return $this->render('AppBundle:Advert:edit.html.twig', array('advert' =>$advert));
    }

    public function deleteAction($id)
    {
        return $this->render('AppBundle:Advert:delete.html.twig');
    }

    public function menuAction($limit)
    {
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche dev Sf'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre stage etc.')
        );
        return $this->render('AppBundle:Advert:menu.html.twig', array(
            'listAdverts' => $listAdverts
            ));
    }

    public function viewSlugAction($year, $slug, $format)
    {
        return new Response(
            "On pourrait afficher l'annonce correspondant au slug '"
            .$slug. "', créée en " .$year. " et au format " .$format."."
        );
    }
}
