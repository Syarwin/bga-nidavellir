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
  public function recruit()
  {
    self::setAjaxMode();
    $cardId = self::getArg("cardId", AT_posint, true);
    $this->game->actRecruit($cardId);
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

  /////////////////////////////
  /////////// Thrud  //////////
  /////////////////////////////
  public function chooseThrudColumn()
  {
    self::setAjaxMode();
    $column = self::getArg("column", AT_posint, true);
    $this->game->actChooseThrudColumn($column);
    self::ajaxResponse();
  }
}
