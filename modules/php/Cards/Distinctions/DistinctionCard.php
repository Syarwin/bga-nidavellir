<?php
namespace NID\Cards\Distinctions;

/*
 * HeroCard: basic holder for hero card
 */

abstract class DistinctionCard extends NID\Cards\AbstractCard
{
  protected $distinctionClass;

  public function __construct($row) {
    parent::__construct($row);
    $this->class = DISTINCTION;
    $this->grade = [
      null,
    ];
  }


  public function getUiData() {
    $data = parent::getUiData();
    $data['distinctionClass'] = $this->distinctionClass;
    $data['subname'] = clienttranslate("(Distinction card)");
    return $data;
  }

  public function updateRanks(&$ranks){}

  public function updateBraveryValues(&$values, $player){}

  public function getRecruitementZone(){
    return NEUTRAL;
  }

  public function applyTieEffect(){}

  public function getDistinctionClass(){
    return $this->distinctionClass;
  }

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
