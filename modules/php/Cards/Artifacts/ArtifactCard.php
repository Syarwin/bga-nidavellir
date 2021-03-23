<?php
namespace NID\Cards\Artifacts;
use Nidavellir;

/*
 * ArtifactCard: all utility functions concerning an artifact
 */

class ArtifactCard extends \NID\Cards\AbstractCard
{
  protected $age;

  public function __construct($row) {
    parent::__construct($row);
    $this->class = ARTIFACT;
  }

  public function isDiscardable() { return false; }

  public function getAge(){ return $this->age; }

  public function getRecruitementZone(){
    return NEUTRAL;
  }

  public function getNotifSymbol(){
    return ARTIFACT;
  }

  public function updateRanks(&$ranks){
  }

  public function updateBraveryValues(&$values, $player){
  }

  public function getNotifString(){
    return sprintf( Nidavellir::translate(clienttranslate("an artifact (%s)")), $this->name);
  }

}
