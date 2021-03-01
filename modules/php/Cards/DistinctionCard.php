<?php
namespace NID\Cards;

/*
 * HeroCard: basic holder for hero card
 */

abstract class DistinctionCard extends AbstractCard
{
  protected $distinctionClass;

  public function __construct($row) {
    parent::__construct($row);
    $this->class = DISTINCTION;
    $this->grade = [
      [null],
    ];
  }


  public function getUiData() {
    $data = parent::getUiData();
    $data['distinctionClass'] = $this->distinctionClass;
    return $data;
  }

  public function updateRanks(&$ranks){}

  public function getRecruitementZone(){
    return NEUTRAL;
  }

  public function getDistinctionClass(){
    return $this->distinctionClass;
  }

  public function applyEffect($player){}

  public function getNotifString(){
    $basicNames = [
      BLACKSMITH => clienttranslate('blacksmiths'),
      HUNTER => clienttranslate('hunters'),
      EXPLORER => clienttranslate('explorers'),
      MINER => clienttranslate('miners'),
      WARRIOR => clienttranslate('warriors'),
    ];

    return $basicNames[$this->getDistinctionClass()];
  }

  public function getNotifSymbol(){
    return $this->getDistinctionClass();
  }
}
