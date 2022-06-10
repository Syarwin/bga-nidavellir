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
