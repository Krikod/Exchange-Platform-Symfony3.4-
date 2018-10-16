<?php
// Service à mettre normalement dans un bundle dédié, pour réutilisation dans d'autres projets.

namespace AppBundle\Antispam;

class OCAntispam
{
    private $mailer;
    private $locale;
    private $minLength;

    public function __construct(\Swift_Mailer $mailer, $locale, $minLength)
    {
        $this->mailer = $mailer;
        $this->locale = $locale;
        $this->minLength = (int) $minLength;

    }

    /**
     * Vérifie si le texte est un spam ou non
     *
     * @param $text
     * @return bool
     */
    public function isSpam($text)
    {
        return strlen($text) < $this->minLength;
    }
}