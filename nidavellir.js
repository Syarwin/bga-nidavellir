/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * Nidavellir implementation : © Timothée Pecatte tim.pecatte@gmail.com
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * nidavellir.js
 *
 * Nidavellir user interface script
 *
 */


var isDebug = window.location.host == 'studio.boardgamearena.com' || window.location.hash.indexOf('debug') > -1;
var debug = isDebug ? console.info.bind(window.console) : function () { };
define([
    "dojo", "dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter",
    g_gamethemeurl + "modules/js/Game/game.js",
    g_gamethemeurl + "modules/js/Game/modal.js",

    g_gamethemeurl + "modules/js/CardTrait.js",
    g_gamethemeurl + "modules/js/CoinTrait.js",
    g_gamethemeurl + "modules/js/PlayerTrait.js",

    g_gamethemeurl + "modules/js/States/BidsTrait.js",
], function (dojo, declare) {
   return declare("bgagame.nidavellir", [
     customgame.game,
     nidavellir.cardTrait,
     nidavellir.coinTrait,
     nidavellir.playerTrait,
     nidavellir.bidsTrait,
   ], {
      constructor(){},

      /*
       * Setup:
       *	This method set up the game user interface according to current game situation specified in parameters
       *	The method is called each time the game interface is displayed to a player, ie: when the game starts and when a player refreshes the game page (F5)
       *
       * Params :
       *	- mixed gamedatas : contains all datas retrieved by the getAllDatas PHP method.
       */
      setup(gamedatas) {
      	debug('SETUP', gamedatas);

        this.setupPlayers();
        this.setupTaverns();
        this.setupRoyalTreasure();

        this.inherited(arguments);
      },


      clearPossible(){
        dojo.query('.coin').removeClass('selected selectable');
        dojo.query(".tavern-coin-holder").removeClass("selectable");

        this.inherited(arguments);
      },
   });
});
