<?php
namespace NID\Cards;
use NID\Cards;
use NID\Game\Notifications;

class DistinctionExplorer extends DistinctionCard
{
  public function __construct($row) {
    parent::__construct($row);
    $this->distinctionClass = EXPLORER;
    $this->name = clienttranslate("Pioneer of the Kingdom");
    $this->tooltip = [
      clienttranslate("Draw 3 cards from the Age 2 deck, keep 1 and shuffled back the two other ones in the Age 2 deck."),
    ];
  }

  public function applyTieEffect(){
    $card = Cards::getTopOf(['age', 2], 1);
    Cards::discard([$card->getId()]);
    Notifications::discardCardExplorerDistinction($card);
  }


  public function stateAfterRecruit(){
    return 'explorer';
  }
}
