<?php
namespace NID\States;
use NID\Game\Globals;
use NID\Game\Players;
use NID\Game\Notifications;
use NID\Game\Stats;
use NID\Game\Log;
use NID\Game\Stack;
use NID\Coins;
use NID\Cards;
use NID\Helpers\Utils;

trait TradeCoinTrait
{
  public function stTradeCoin()
  {
    $player = Players::getActive();
    if ($player->shouldTrade()) {
      $coins = $player->getUnbidCoins();

      // Handle Uline transform when > 2 coins in her hand
      $ulineOwnerId = Cards::getUlineOwner();
      if (count($coins) > 2 && $player->getId() == $ulineOwnerId) {
        $this->gamestate->changeActivePlayer($ulineOwnerId);
        $this->gamestate->nextState('uline');
        return;
      }

      $this->tradeCoin($player, $coins[0], $coins[1]);
    }

    $this->gamestate->nextState('next');
  }

  public function tradeCoin($player, $coin1, $coin2)
  {
    $coinMin = $coin1['value'] <= $coin2['value'] ? $coin1 : $coin2;
    $coinMax = $coin1['value'] <= $coin2['value'] ? $coin2 : $coin1;
    $target = $coinMin['value'] + $coinMax['value'];
    if (Cards::getJarikaOwner() == $player->getId()) {
      $target += 2;
    }

    // Zolkur swap
    if (Cards::getZolkurOwner() == $player->getId()) {
      $zolkur = Cards::get(ZOLKUR);
      if ($zolkur->getZone() == ZOLKUR_ZONE) {
        $tmp = $coinMin;
        $coinMin = $coinMax;
        $coinMax = $tmp;
        Cards::recruit($zolkur, $player->getId(), NEUTRAL);
        Cards::refresh($zolkur); // Update location
        Notifications::ZolkurEffect($player, $zolkur);
      }
    }

    $newCoin = Coins::trade($coinMax, $target);
    $newValue = Coins::get($newCoin)['value'];
    if ($newValue > $target) {
      Cards::increaseForce(SVAFA, Players::getActive()); // Valkyrie
    }

    Notifications::tradeCoin($player, $coinMin, $coinMax, $newCoin);
    Players::updateScores();
    Stats::upgradeCoin($player);
  }

  public function argUlineTradeCoin()
  {
    return $this->argUlineBid(); // Actually the same arg as Uline bid !
  }

  public function actUlineTrade($coinIds)
  {
    $coins = Coins::get($coinIds)->toArray();
    $this->tradeCoin(Players::getActive(), $coins[0], $coins[1]);
    $this->gamestate->nextState('next');
  }

  /**************************
   ***** Transform coins *****
   **************************/
  public function argTransformCoin()
  {
    $player = Players::getActive();

    $coins = $player->getCoins();
    $upgradableCoins = [];
    foreach ($coins as $coin) {
      if ($coin['value'] != 0 && ($coin['value'] != 3 || $coin['type'] != COIN_DISTINCTION)) {
        $upgradableCoins[] = $coin['id'];
      }
    }

    return [
      'value' => Globals::getTransformValue(),
      'coins' => $upgradableCoins,
    ];
  }

  public function actTransformCoin($coinId)
  {
    $this->checkAction('transform');

    // Check if coins belongs to player
    $coin = Coins::get($coinId);
    $player = Players::getCurrent();
    if ($coin['pId'] != $player->getId()) {
      throw new \BgaUserException(_('This coin is not yours!'));
    }

    $player->tradeCoin($coin, Globals::getTransformValue());

    $newState = $player->canRecruitHero() ? 'hero' : null;
    Stack::nextState('transformDone', $newState);
  }

  /*******************
   ***** VIDOFNIR *****
   *******************/
  public function argVidofnirTransform()
  {
    $player = Players::getActive();
    $data = [
      'transformations' => [2, 3],
      'coins' => $player->getUnbidCoins(),
    ];

    // Remove non tradable coins
    Utils::filter($data['coins'], function ($coin) {
      return $coin['value'] != 0 && ($coin['value'] != 3 || $coin['type'] != COIN_DISTINCTION);
    });

    // Only one upgradable coin ? => + 5
    if (count($data['coins']) == 1) {
      $data['transformations'] = [5];
    }

    // Jurika owner +2 for each transformations
    if (Cards::getJarikaOwner() == $player->getId()) {
      $data['transformations'] = array_map(function ($t) {
        return $t + 2;
      }, $data['transformations']);
    }

    // Already made a transformation ? Remove transformation and coin
    $previousTransform = $player->getLastAction('vidofnir');
    if ($previousTransform != null) {
      Utils::filter($data['transformations'], function ($t) use ($previousTransform) {
        return $t != $previousTransform['upgrade'];
      });
      Utils::filter($data['coins'], function ($coin) use ($previousTransform) {
        return $coin['id'] != $previousTransform['coinId'];
      });
    }

    // Format coins
    $data['coins'] = array_map(function ($coin) {
      return $coin['id'];
    }, $data['coins']);

    return $data;
  }

  public function stVidofnirTransform()
  {
    $data = $this->argVidofnirTransform();

    // Only one choice of transformation and of coins ? => automatically upgrade it
    if (count($data['coins']) == 1 && count($data['transformations']) == 1) {
      $this->actVidofnirUpgrade($data['coins'][0], $data['transformations'][0]);
    }
  }

  public function actVidofnirUpgrade($coinId, $transform)
  {
    self::checkAction('vidofnirTransform');

    $coin = Coins::get($coinId);
    $player = Players::getCurrent();
    if ($coin['pId'] != $player->getId()) {
      throw new \BgaUserException(_('This coin is not yours!'));
    }

    $data = $this->argVidofnirTransform();
    if (!in_array($coinId, $data['coins']) || !in_array($transform, $data['transformations'])) {
      throw new \BgaUserException(_('You cannot make this coin transformation!'));
    }

    $newCoin = $player->tradeCoin($coin, $transform);
    Log::insert($player, 'vidofnir', [
      'upgrade' => $transform,
      'coinId' => $newCoin['id'],
    ]);

    $this->gamestate->nextState(count($data['transformations']) == 1 ? 'done' : 'vidofnir');
  }
}
?>
