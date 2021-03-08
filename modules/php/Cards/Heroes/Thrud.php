<?php
namespace NID\Cards\Heroes;

class Thrud extends HeroCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = THRUD;
    $this->name = 'Thrud';
    $this->subname = clienttranslate("The Headhunter");
    $this->tooltip = [
      clienttranslate("Add 13 points to your final Bravery Value."),
      clienttranslate("Place her in the column of your choice in your army. Thrud should never be covered. If a Dwarf of Hero card should be placed on Thrud, take Thrud in thand, put the Dwarf or Hero card in the column, and replace Thrud in the column of your choice. Placing her may trigger the recruitment of a new Hero card"),
      clienttranslate("The rank of Thrud counts in the attribution of Distinctions of Age 1 in the column where she is. After the resolution of the last Age 2 Tavern and before the countdown of your final Bravery Value, she is placed in your Command Zone."),
    ];
    $this->heroClass = NEUTRAL;
    $this->advanced = true;
    $this->grade = [
      13
    ];
  }

  public function updateRanks(&$ranks){
    $ranks[$this->zone] += $this->getRanks();
  }
}
