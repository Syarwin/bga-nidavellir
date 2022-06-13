<?php
namespace NID\Cards\Ases;
use Nidavellir;

/*
 * AseCard: all utility functions concerning an Ase
 */

class AseCard extends \NID\Cards\AbstractCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->class = ASE;
    $this->tooltip = [
      clienttranslate(
        'As soon as you recruit one of the gods, put the card in your Command Zone with 1 Power token on it.'
      ),
      clienttranslate(
        'You may activate his or her ability once in a game by discarding the Power Token of the matching God card.'
      ),
    ];
  }

  public function getRecruitementZone()
  {
    return NEUTRAL;
  }

  public function getNotifSymbol()
  {
    return ASE;
  }

  public function updateRanks(&$ranks, $uselessExceptThrud)
  {
  }

  public function updateBraveryValues(&$values, $player)
  {
  }

  public function getNotifString()
  {
    return sprintf(Nidavellir::translate(clienttranslate('an ase (%s)')), $this->name);
  }
}
