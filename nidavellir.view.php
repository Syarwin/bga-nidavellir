<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * Nidavellir implementation : © Timothée Pecatte tim.pecatte@gmail.com
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * nidavellir.view.php
 *
 *
 */

require_once( APP_BASE_PATH."view/common/game.view.php" );

class view_nidavellir_nidavellir extends game_view
{
  function getGameName() {
    return "nidavellir";
  }

  function build_page($viewArgs){
    $this->tpl['TURN'] = self::_("Turn");
    $this->tpl['SCORE'] = self::_("Score");
  }
}
