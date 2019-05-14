<?php

namespace AppBundle\Controller;

use AppBundle\Form\AdvertType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use AppBundle\Entity\Advert;
use AppBundle\Entity\Image;
use AppBundle\Entity\Application;
use AppBundle\Entity\AdvertSkill;
use Symfony\Component\Validator\Constraints\DateTime;

class AdvertController extends Controller
{
    public function indexAction($page)
    {
        if ($page < 1 ) {
            throw new NotFoundHttpException(
                'Page "' .$page.'" inexitante');
        }

        $categoryNames = array(
            'Développement web',
//            'Développement mobile',
//            'Graphisme',
//            'Intégration',
            'Réseau'
        );

        $repo = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Advert');

        $listAdverts = $repo->getAdvertWithCategories($categoryNames);

        return $this->render('AppBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts,
            'categoryNames' => $categoryNames
        ));
    }

    public function indexAppliAction($page)
    {
        $limit = 5;

        if ($page < 1 ) {
            throw new NotFoundHttpException(
                'Page "' .$page.'" inexitante');
        }

        $repo = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Application');

        $listAppli = $repo->getApplicationsWithAdvert($limit);

        return $this->render('AppBundle:Advert:indexAppli.html.twig', array(
            'listAppli' => $listAppli,
            'limit' => $limit
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
//    $repo = $this->getDoctrine()->getRepository(Advert::class);
//    // Qd on a l’O Repository, on a accès à diverses méthodes:
//    $advert = $repo->find($id);

//    $advert = $this->getDoctrine()
//        ->getRepository('AppBundle:Advert')
//        ->find($id);

    $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $advert = $em->getRepository('AppBundle:Advert')->find($id);

    // $advert est donc une instance de AppBundle\Entity\Advert
    // ou ! si $id n'existe pas, d'où ce if (null si on avait écrit
    // if (null === $advert) - !advert conforme à la doc:
    if (!$advert) {
        throw $this->CreateNotFoundException("L'annonce d'id ".$id." n'existe pas.");
    }

    // On récup. liste des cand. à cette annonce
    $listApplications = $em
        ->getRepository('AppBundle:Application')
        ->findBy(array('advert' => $advert));

    // On récupère la liste des AdvertSkill
    $listAdvertSkills = $em
        ->getRepository('AppBundle:AdvertSkill')
        ->findBy(array('advert' => $advert));

    return $this->render('AppBundle:Advert:view.html.twig', array(
            'advert' => $advert,
            'listApplications' => $listApplications,
            'listAdvertSkills' => $listAdvertSkills
    ));

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
    {
        $advert = new Advert();

        $form = $this->createForm(AdvertType::class, $advert);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($advert);
                $em->flush();

                $this->addFlash('info', 'Annonce bien enregistrée');
                // Ds la vue -->
            //          {% for message in app.session.flashbag.get('info') %}
            //             <div class="alert alert-info">{{ message }}</div>
            //          {% endfor %}
                return $this->redirectToRoute('platform_view', array(
                    'id' => $advert->getId()
                ));
        }

        // On passe méthode createView() du formulaire à la vue
        return $this->render('AppBundle:Advert:add.html.twig', array(
            'form' => $form->createView(),
        ));

        /*
        // UTILISATION D'UN SERViCE ANTISPAM:
        // On récup serv (app.antispam depreciated !!!)
        $antispam = $this->container->get('app.antispam');

        // $text contient texte d'un message quelconque
        $text = 'Texte de moins de 50 caractères est un spam. Donc pas spam:
        jdnjfdsjfnsdjffnsdjvndsjnvsv nfencecneunceunezvnzuvnzuvn ncuencuencuencuencuencencuec
        kfdfndsnfjdsnflqf nfqlnflqnfqln';
        if ($antispam->isSpam($text)) {
            throw new \Exception('Votre message a été détecté comme spam !');
        }
// Ici message n'est pas un spam:

        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // CREATION ENTITE ADVERT
        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony.');
        $advert->setAuthor('Alexandre');
        $advert->setContent('Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…');
        $date = new \DateTime();
        $date->setDate(2001, 2, 3);
        $advert->setDate($date);
        // On peut ne pas définir "publication": attributs définis autom. dans constructeur

        // On récupère toutes les compétences possibles
        $listSkills = $em
            ->getRepository('AppBundle:Skill')
            ->findAll();

        // Pour chaque compétence
        foreach ($listSkills as $skill) {
            // On crée 1 nvelle "rel. entre 1 annonce
            // et 1 compétence"
            $advertSkill = new AdvertSkill();

            // On la lie à l'annonce qui est ici toujours la même
            $advertSkill->setAdvert($advert);
            // On la lie à la compétence, qui change ici dans la boucle
            $advertSkill->setSkill($skill);

            // Arbitrairement, on dit que chaque compétence est requise
            // au niveau "Expert"
            $advertSkill->setLevel('Expert');

            // On persite cette entité de relation, proprio
            // des 2 autres relations
            $em->persist($advertSkill);
        }
        // Doctrine ne connait pas encore l'E $advert.
        // Si on n'a pas défini la rel. AdvertSkill avec un cascade persist
        // (ce qui est le cas si on a utilisé le code OCR), alors on doit persist $advert
        // --> voir + bas le persist

        // Création d'une 1ère candidature
        $application1 = new Application();
        $application1->setAuthor('Marine');
        $application1->setContent("J'ai toutes les qualités requises.");

        // Création d'une 2e candidature par ex.
        $application2 = new Application();
        $application2->setAuthor('Pierre');
        $application2->setContent("Je suis très motivé.");

        // On lie les candidatures à l'annonce
        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

        // CREATION ENTITE IMAGE
        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de rêve');

        // On lie l'image à l'annonce:
        $advert->setImage($image);


        // Étape 1 : On « persiste » l'entité
        $em->persist($advert); // cascade={'persist'} donc img aussi persistée (sinon persister mano)
        // // Étape 1 bis : pour cette rel. pas de cascade qd on persist Advert, car la rel. est
        // définie ds l'E Application et non Advert. On doit donc tout persister à la main ici.
        $em->persist($application1);
        $em->persist($application2);

        // Étape 2 : On « FLUSH » tout ce qui a été persisté avant
        $em->flush();

        // Reste de la méthode qu'on avait déjà écrit
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('info', 'Annonce bien enregistrée.');
            // Puis on redirige vers la page de visualisation de cettte annonce

//            return $this->redirectToRoute('platform_view', array('id' =>5));
            return $this->redirectToRoute('platform_view', array('id' => $advert->getId()));
        }
        // Flashbag à effacer plus tard car ici pas POST
        $session = $request->getSession();
        $session->getFlashBag()->add('info', 'Annonce bien enregistrée.');

        // Si on n'est pas en POST, on affiche le formulaire
        return $this->render('AppBundle:Advert:add.html.twig', array('advert' => $advert));
        */
    }

    // Méth. qui modifierait l'img déjà existante d'1 annonce
    public function editImageAction($advertId)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('AppBundle:Advert')
            ->find($advertId);
        $advert->getImage()->seturl('test.png');
        $em->flush();

        return new Response('OK');
    }

    public function editAction($id, Request $request)
    {
        // LIER ANNONCE A CATEGORIES
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('AppBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        // La méth. findAll() return ttes les CATEGORIES de la bdd
        $listCategories = $em->getRepository('AppBundle:Category')->findAll();

        // On BOUCLE sur les CATEG. pour les lier à l'annonce
        // 2 lignes qui concernent le Many-To-Many:
        foreach ($listCategories as $category) {
            $advert->addCategory($category);
        }

        // Pour persister le changt dans la rel., il faut persister l'E proprio
        // Ici, Advert est le proprio, donc inutile de la persist car récupérée depuis Doctrine

        // Etape 2: on déclenche l'enregistrement
        $em->flush();

        // RESTE DE LA METHODE:

        // Récupérer annonce
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
        $advert->updateDate();


        return $this->render('AppBundle:Advert:edit.html.twig', array(
            'advert' =>$advert
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        // DETACHEMENT DE L'ADVERT DES CATEGORIES
        // On récupère l'annonce $id
        $advert = $em->getRepository('AppBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException(
                "L'annonce d'id ".$id." n'existe pas."
            );
        }

        // On boucle sur les catégories de l'annonce pour les supprimer
        foreach ($advert->getCategories() as $category) {
            $advert->removeCategory($category);
        }

        // Pour persister le changement dans la rel., il faut persister l'E proprio
        // Ici, Advert est le proprio, donc inutile de la persist car on l'a récupérée depuis D.

        // On déclenche la modification
        $em->flush();

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
            // Tout l'intérêt est ici : le C passe les variables nécessaires au template !
            'listAdverts' => $listAdverts
            ));
    }

    public function purge($days)
    {

    }

//    Meth. non utilisée
    public function viewSlugAction($year, $slug, $format)
    {
        return new Response(
            "On pourrait afficher l'annonce correspondant au slug '"
            .$slug. "', créée en " .$year. " et au format " .$format."."
        );
    }
}

/* Pour indexAction:

$content = $this
//            ->container
//            ->get('templating')
//            ->render('AppBundle:Advert:index.html.twig', array('nom' => 'winzou'));
//        return new Response($content);


// Liste d'annonces en dur pour indexAction():
 $listAdverts = array(
//            array(
//                'title'   => 'Recherche développpeur Symfony',
//                'id'      => 1,
//                'author'  => 'Alexandre',
//                'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
//                'date'    => new \Datetime()),
//
//            array(
//                'title'   => 'Mission dév Drupal 8',
//                'id'      => 2,
//                'author'  => 'Jeanne',
//                'content' => 'Une super boîte (Drup18) renforce ses équipes en compétence Drupal - Paris. Rejoignez-les…',
//                'date'    => new \Datetime()),
//
//            array(
//                'title'   => 'Recherche urgente débutant PHP/Symfony 3/4',
//                'id'      => 3,
//                'author'  => 'Fabien Potencier',
//                'content' => 'Sensiolabs manque de grands débutants Symfony ! Incroyable mais vrai ;)…',
//                'date'    => new \Datetime()),
//
//            array(
//
//                'title'   => 'Mission de webmaster',
//                'id'      => 4,
//                'author'  => 'Hugo',
//                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
//                'date'    => new \Datetime()),
//
//            array(
//
//                'title'   => 'Offre de stage webdesigner',
//                'id'      => 5,
//                'author'  => 'Mathieu',
//                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
//                'date'    => new \Datetime())
//        );
 */