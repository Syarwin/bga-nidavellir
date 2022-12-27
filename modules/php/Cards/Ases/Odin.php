<?php
namespace NID\Cards\Ases;
use NID\Game\Players;

class Odin extends AseCard
{
  public function __construct($row)
  {
    parent::__construct($row);
    $this->id = ODIN;
    $this->name = 'Odin';
    $this->tooltip[] = clienttranslate(
      'At the end of your turn, you may put one of your Neutral Heroes back in the reserve and recruit another Neutral Hero instead.'
    );
    $this->tooltip[] = clienttranslate('Then, possibly apply the effect of the newly recruited Hero.');
    $this->grade = [0];
  }

  public function canUsePower()
  {
    if (!parent::canUsePower()) {
      return false;
    }

    return !empty($this->getNeutralHeroes());
  }

  public function getNeutralHeroes()
  {
    $player = Players::get($this->pId);
    $heroes = $player->getCards()->filter(function ($card) {
      return $card->getClass() == HERO && ($card->getRecruitementZone() == NEUTRAL || $card->getId() == ZOLKUR);
    });
    $cards = [];
    foreach ($heroes as $hero) {
      $cards[$hero->getId()] = $hero->getName();
    }

    return $cards;
  }
}
