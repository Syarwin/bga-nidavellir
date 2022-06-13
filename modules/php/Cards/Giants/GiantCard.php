<?php
namespace NID\Cards\Giants;
use Nidavellir;
use NID\Cards;
use NID\Game\Notifications;
use NID\Game\Players;

/*
 * GiantCard: all utility functions concerning a Giant
 */

class GiantCard extends \NID\Cards\AbstractCard
{
  protected $giantClass = null;
  public function __construct($row)
  {
    parent::__construct($row);
    $this->class = GIANT;
  }

  public function getUiData()
  {
    $data = parent::getUiData();
    $data['giantClass'] = $this->giantClass;
    return $data;
  }

  public function getActivationStatus()
  {
    return $this->flag;
  }

  public function canCapture($card)
  {
    return $this->getActivationStatus() == GIANT_PENDING &&
      $card instanceof \NID\Cards\DwarfCard &&
      $card->getRecruitementZone() == $this->giantClass;
  }

  public function denyCapture()
  {
    Cards::DB()->update(['flag' => 2], $this->id);
  }

  public function capture($card)
  {
    Cards::insertAt($card->getId(), $this->location, $this->state);
    Cards::refresh($card);
    Notifications::capture(Players::get($this->pId), $this, $card);
    Cards::DB()->update(['flag' => 1], $this->id);
  }

  public function applyEffect($player)
  {
  }

  public function getRecruitementZone()
  {
    return NEUTRAL;
  }

  public function getNotifSymbol()
  {
    return GIANT;
  }

  public function updateRanks(&$ranks, $uselessExceptThrud)
  {
  }

  public function updateBraveryValues(&$values, $player)
  {
  }

  public function getNotifString()
  {
    return sprintf(Nidavellir::translate(clienttranslate('a giant (%s)')), $this->name);
  }
}
