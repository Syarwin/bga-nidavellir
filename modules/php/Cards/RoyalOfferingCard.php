<?php
namespace NID\Cards;

/*
 * RoyalOfferingCard
 */

class RoyalOfferingCard extends AbstractCard
{
  public function getValue(){
    return $this->grade[0];
  }

  public function getRecruitementZone(){
    return ROYAL_OFFER."_".$this->getValue();
  }
}
