<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Advert;
use Symfony\Component\Validator\Constraints\DateTime;

class LoadAdverts extends Fixture
{
public function load(ObjectManager $manager)
{
    // Liste des Adverts à ajouter
    $as = array(
        array(
            'title'   => 'Recherche développpeur Symfony',
            'id'      => 1,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
            'date' => new \DateTime('2001-03-03 14:05:58')),

        array(
            'title'   => 'Mission dév Drupal 8',
            'id'      => 2,
            'author'  => 'Jeanne',
            'content' => 'Une super boîte (Drup18) renforce ses équipes en compétence Drupal - Paris. Rejoignez-les…',
            'date' => new \DateTime('2001-03-03 14:05:58')),

        array(
            'title'   => 'Recherche urgente débutant PHP/Symfony 3/4',
            'id'      => 3,
            'author'  => 'Fabien Potencier',
            'content' => 'Sensiolabs manque de grands débutants Symfony ! Incroyable mais vrai ;)…',
            'date' => new \DateTime('2001-03-03 14:05:58')),

        array(

            'title'   => 'Mission de webmaster',
            'id'      => 4,
            'author'  => 'Hugo',
            'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
            'date' => new \DateTime('2001-03-03 14:05:58')),

        array(

            'title'   => 'Offre de stage webdesigner',
            'id'      => 5,
            'author'  => 'Mathieu',
            'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
            'date' => new \DateTime('2001-03-03 14:05:58')),
        );

        foreach ($as as $a) {
            // On crée l'advert
            $advert = new Advert();
            $advert->setDate($a['date']);
            $advert->setTitle($a['title']);
            $advert->setAuthor($a['author']);
            $advert->setContent($a['content']);

            // On la persiste
            $manager->persist($advert);
        }
            // On déclenche l'enregistrement de toutes les catégories
            $manager->flush();
    }
}