<?php
/**
 * Created by PhpStorm.
 * User: krikod
 * Date: 05/11/18
 * Time: 11:48
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Skill;

class LoadSkill extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Liste des compétences à ajouter
        $names = array('PHP', 'Symfony', 'C++', 'Java',
            'Photoshop', 'Blender', 'Bloc-note');

        foreach ($names as $name) {
            // On crée la compétence
            $skill = new Skill();
            $skill->setName($name);

            // On la persiste
            $manager->persist($skill);
        }
        // On déclenche l'enregistrement de toutes les catégories
        $manager->flush();
    }
}