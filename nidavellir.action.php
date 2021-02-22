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
}
