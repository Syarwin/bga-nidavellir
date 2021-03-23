<?php
namespace NID\Cards\Mercenaries;

/*
 * MercenaryCard: all utility functions concerning a mercenary
 */

class MercenaryCard extends \NID\Cards\AbstractCard
{
  protected $age = null;
  protected $grades = []; // Not to be confused with $grade for standard card

  public function __construct($row) {
    parent::__construct($row);
    $this->grade = [null];
  }

  public function getUiData() {
    $data = parent::getUiData();
    $data['grades'] = $this->grades;
    return $data;
  }

  public function getAge(){ return $this->age; }

  public function getTooltip(){
    return [
      clienttranslate("Place it in your command zone. At the end of an age, you must put it in your army in one of the two listed class."),
    ];
  }

  public function getName(){
    return clienttranslate("Mercenaries");
  }

  public function getRecruitementZone(){
    return NEUTRAL;
  }

  public function getNotifSymbol(){
    return MERCENARY;
  }

  public function updateRanks(&$ranks){
    if($this->zone != NEUTRAL){
      $ranks[$this->zone]++;
    }
  }

  public function updateBraveryValues(&$values, $player){
  }
}
