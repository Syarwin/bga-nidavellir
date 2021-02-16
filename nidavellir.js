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
    g_gamethemeurl + "modules/js/States/RecruitTrait.js",
], function (dojo, declare) {
   return declare("bgagame.nidavellir", [
     customgame.game,
     nidavellir.cardTrait,
     nidavellir.coinTrait,
     nidavellir.playerTrait,
     nidavellir.bidsTrait,
     nidavellir.recruitTrait,
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
        dojo.query(".tavern-cards-holder").removeClass("selectable");


        this.inherited(arguments);
      },



      /* This enable to inject translatable styled things to logs or action bar */
      /* @Override */
      format_string_recursive (log, args) {
        try {
          if (log && args && !args.processed) {
            args.processed = true;

            // Representation of the class of a card
            if (args.card_class !== undefined) {
              args.card_class = dojo.string.substitute("<span class='card-class class-${class}'></span>", {'class' :args.card_class });
            }
          }
        } catch (e) {
          console.error(log,args,"Exception thrown", e.stack);
        }

        return this.inherited(arguments);
      },

   });
});
