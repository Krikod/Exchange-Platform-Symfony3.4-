<?php
// Service à mettre normalement dans un bundle dédié, pour réutilisation dans d'autres projets.

namespace AppBundle\Purger;

class OCPlatformPurgerAdvert
{
    private $maxDays;

    public function __construct($maxDays)
    {
        $this->maxDays = (int) $maxDays;

    }

    /**
     * Vérifie si Annonce a + de $days jours
     *
     */
    public function purge($days)
    {
        return $days > $this->maxDays;
    }
}