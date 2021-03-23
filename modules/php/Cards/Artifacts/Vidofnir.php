<?php
namespace NID\Cards\Artifacts;

class Vidofnir extends ArtifactCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->id = VIDOFNIR;
    $this->age = 1;
    $this->name = 'Vidofnir & Vedrfolnir';
    $this->tooltip = [
      clienttranslate("Immediately reveal the coins from your pouch and transform one of these coins with a +2 and the other with a +3."),
      clienttranslate("If one of the coins on the trade is the Trading coin (the 0 or the Special Hunter 3), then apply a +5 transform to the other coin."),
      clienttranslate("Perform coin transformations in any order you want"),
    ];
    $this->grade = [ null ];
  }

  public function stateAfterRecruit($player){
    return 'vidofnir';
  }
}
