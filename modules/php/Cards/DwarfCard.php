<?php
namespace NID\Cards;

/*
 * DwarfCard: all utility functions concerning a card : dwarf/improvements or hero
 */

class DwarfCard extends AbstractCard
{
  public function getTooltip(){
    $tooltips = [
      WARRIOR => [
        clienttranslate("Their Bravery Value is equal to the sum of their Bravery Points, to which the Elvaland who gets majority in ranks in the Warrior column, add his coin of highest value."),
        clienttranslate("In case of a tie, all tied Elvalands add their highest value coin to their Warrior Bravery Value.")
      ],
      HUNTER => [
        clienttranslate("Their Bravery Value is equal to the number of Hunters squared :"),
        clienttranslate("0, 1, 4, 9, 16, 25, 36, 49, 64, 81, 100, 121, 144, 169, 196, 225, 256, 289, ..."),
      ],
      MINER => [
        clienttranslate("Their Bravery Value is equal to othe sum of their Bravery Points multiplied by the number of ranks in their column.")
      ],
      BLACKSMITH => [
        clienttranslate("Their Bravery Value is a mathematical sequence (+3, +4, +5, +6, ...) :"),
        clienttranslate("0, 3, 7, 12, 18, 25, 33, 42, 52, 63, 75, 88, 102, 117, 133, 150, 168, 187, 207, ...")
      ],
      EXPLORER => [
        clienttranslate("Their Bravery Value is equal to the sum of their Bravery Points.")
      ],
    ];

    return $tooltips[$this->class];
  }

  public function getName(){
    $names = [
      WARRIOR => clienttranslate("Warriors"),
      HUNTER => clienttranslate("Hunters"),
      MINER => clienttranslate("Miners"),
      BLACKSMITH => clienttranslate("Blacksmith"),
      EXPLORER => clienttranslate("Explorers"),
    ];

    return $names[$this->class];
  }
}
