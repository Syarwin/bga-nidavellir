<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * Nidavellir implementation : © Timothée Pecatte tim.pecatte@gmail.com
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 *
 * nidavellir.action.php
 *
 * Nidavellir main action entry point
 */


class action_nidavellir extends APP_GameAction
{
  // Constructor: please do not modify
  public function __default()
  {
    if( self::isArg( 'notifwindow') ){
      $this->view = "common_notifwindow";
      $this->viewArgs['table'] = self::getArg( "table", AT_posint, true );
    } else {
      $this->view = "nidavellir_nidavellir";
      self::trace( "Complete reinitialization of board game" );
    }
  }


  public function setAutopick()
  {
    self::setAjaxMode();
    $mode = self::getArg("autopick", AT_posint, true);
    $this->game->actSetAutopick($mode);
    self::ajaxResponse();
  }


  ///////////////////////////////
  //////////// Bids  ////////////
  ///////////////////////////////
      public function autobid()
      {
        self::setAjaxMode();
        $this->game->actAutobid();
        self::ajaxResponse();
      }

  public function playerBid()
  {
    self::setAjaxMode();
    $coinId = self::getArg("coinId", AT_posint, true);
    $tavern = self::getArg("tavern", AT_numberlist, true);
    $this->game->actPlayerBid($coinId, $tavern);
    self::ajaxResponse();
  }

  public function confirmBids()
  {
    self::setAjaxMode();
    $this->game->actConfirmBids();
    self::ajaxResponse();
  }

  public function changeBids()
  {
    self::setAjaxMode();
    $this->game->actChangeBids();
    self::ajaxResponse();
  }


  ///////////////////////////////
  /////////// Recruit  //////////
  ///////////////////////////////
      public function pass()
      {
        self::setAjaxMode();
        $this->game->gamestate->nextState('recruitDone');
        self::ajaxResponse();
      }

  public function recruit()
  {
    self::setAjaxMode();
    $cardId = self::getArg("cardId", AT_posint, true);
    $capture = self::getArg("capture", AT_bool, false, false);
    $this->game->actRecruit($cardId, $capture);
    self::ajaxResponse();
  }


  public function transformCoin()
  {
    self::setAjaxMode();
    $coinId = self::getArg("coinId", AT_posint, true);
    $this->game->actTransformCoin($coinId);
    self::ajaxResponse();
  }



  public function discardCards()
  {
    self::setAjaxMode();
    $raw = self::getArg("cardIds", AT_numberlist, true);
    $cardIds = explode(';', $raw);
    $this->game->actDiscardCards($cardIds);
    self::ajaxResponse();
  }


  public function discardTavern()
  {
    self::setAjaxMode();
    $cardId = self::getArg("cardId", AT_posint, true);
    $this->game->actDiscardTavernCard($cardId);
    self::ajaxResponse();
  }

  public function discardHofud()
  {
    self::setAjaxMode();
    $cardId = self::getArg("cardId", AT_posint, true);
    $this->game->actDiscardHofud($cardId);
    self::ajaxResponse();
  }


  /////////////////////////////
  /////////// Uline  //////////
  /////////////////////////////
  public function ulineBid()
  {
    self::setAjaxMode();
    $coinId = self::getArg("coinId", AT_posint, true);
    $this->game->actUlineBid($coinId);
    self::ajaxResponse();
  }

  public function ulineTrade()
  {
    self::setAjaxMode();
    $raw = self::getArg("coinIds", AT_numberlist, true);
    $coinIds = explode(';', $raw);
    $this->game->actUlineTrade($coinIds);
    self::ajaxResponse();
  }

  ////////////////////////////////////
  /////////// Thrud & Ylud  //////////
  ////////////////////////////////////
  public function chooseColumn()
  {
    self::setAjaxMode();
    $column = self::getArg("column", AT_posint, true);
    $this->game->actChooseColumn($column);
    self::ajaxResponse();
  }


  //////////////////////////////////////////////
  /////////// Thingvellir Mercenaries //////////
  //////////////////////////////////////////////
  public function chooseOrder()
  {
    self::setAjaxMode();
    $position = self::getArg("position", AT_posint, true);
    $this->game->actChooseOrder($position);
    self::ajaxResponse();
  }


  public function enlist()
  {
    self::setAjaxMode();
    $cardId = self::getArg("cardId", AT_posint, true);
    $column = self::getArg("column", AT_posint, true);
    $this->game->actEnlistMercenary($cardId, $column);
    self::ajaxResponse();
  }


  ////////////////////////////////
  /////////// Artifacts //////////
  ////////////////////////////////
  public function vidofnirUpgrade()
  {
    self::setAjaxMode();
    $coinId = self::getArg("coinId", AT_posint, true);
    $transform = self::getArg("transform", AT_posint, true);
    $this->game->actVidofnirUpgrade($coinId, $transform);
    self::ajaxResponse();
  }
}
