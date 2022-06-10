<?php
namespace NID\Cards\Heroes;

class Zolkur extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->thingvellir = true;
    $this->id = ZOLKUR;
    $this->name = 'Zolkur';
    $this->subname = clienttranslate("The Greedy");
    $this->tooltip = [
      clienttranslate("Add 10 points to your Final Bravery Value."),
      clienttranslate("During the next trade after you recruited him, you trade the lower value coin instead of the higher. Then return Zolkur's card to the Command Zone.")
    ];
    $this->heroClass = NEUTRAL;
    $this->grade = [
      10
    ];
  }

  public function getRecruitementZone(){
    return ZOLKUR_ZONE;
  }
}
