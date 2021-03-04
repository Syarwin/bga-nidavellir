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

        this.setupOverview();
        this.setupPlayers();
        this.setupCards();

        this.setupRoyalTreasure();
        this.setupInfoPanel();
        this.udpateInfoCounters();

        dojo.toggleClass("ebd-body", "nidavellir-expansion", gamedatas.expansion);

        this.inherited(arguments);
      },


      clearPossible(){
        dojo.query('.coin').removeClass('selected selectable');
        dojo.query(".tavern-coin-holder").removeClass("selectable");
        dojo.query(".tavern-cards-holder").removeClass("selectable");
        dojo.query(".card").removeClass("selectable unselectable");
        dojo.query(".nid-tab").removeClass("focus");
        dojo.query('.command-zone-container .cards-class').removeClass("selectable");
        if(this._distinctionExplorerCards != null)
          this._distinctionExplorerModal.destroy();

        this.inherited(arguments);
      },

      onUpdateActionButtons(){
        if(isDebug){
          this.addPrimaryActionButton('btnTest', 'Autobid', () => this.takeAction('autobid') );
        }

        this.inherited(arguments);
      },


      setupInfoPanel(){
        this.place('jstpl_configPlayerBoard', {
          'age' : _('Age'),
          'turn' : _('Turn'),
        }, 'player_boards', 'first');
        dojo.connect($('show-settings'), 'onclick', () => this.toggleControls() );
        dojo.connect($('tab-score'), 'onclick', () => this.showOverview() );
        this.turnCounter = new ebg.counter();
        this.turnCounter.create('turn-counter');

        this.addTooltip( 'show-settings', '', _("Display some settings about the game."));
        this.addTooltip( 'show-overview', '', _("Display the scoring details."));

        this.setupSettings();
      },

      udpateInfoCounters(){
        dojo.attr('age-counter', 'data-value', this.gamedatas.age);
        this.turnCounter.toValue(this.gamedatas.turn);
        dojo.attr('ebd-body', 'data-tavern', this.gamedatas.tavern);
        dojo.attr('ebd-body', 'data-age', this.gamedatas.age);
      },


      updatePlayerOrdering(){
        this.inherited(arguments);
        dojo.place('player_board_config', 'player_boards', 'first');
      },


      //////////////////////////////
      //////////////////////////////
      /////////   SETTINGS   ///////
      //////////////////////////////
      //////////////////////////////

      /*
       * Display a table with a nice overview of current situation for everyone
       */
      setupOverview(){
        this._overviewModal = new customgame.modal("showOverview", {
          class:"nidavellir_popin",
          closeIcon:'fa-times',
          openAnimation:true,
          openAnimationTarget:"tab-score",
          contents:jstpl_overview,
          statusElt:'tab-score',
          closeAction:'hide',
        });

        for(var pId in this.gamedatas.players){
          let player = this.gamedatas.players[pId];

          dojo.place('<th>' + player.name + '</th>', 'overview-headers');
          for(var i = 0; i < 8; i++){
            dojo.place('<td id="overview-' + pId + '-'+ i + '"></td>', "overview-row-" + i);
          }
          dojo.place('<td id="overview-' + pId + '-total"></td>', "overview-total");
        }
      },

      showOverview(){
        debug("Showing overview:");
/*
        let box = $("ebd-body").getBoundingClientRect();
        let modalWidth = 1000;
        let newModalWidth = box['width']*0.8;
        let modalScale = newModalWidth / modalWidth;
        if(modalScale > 1) modalScale = 1;
        dojo.style("popin_showOverview", "transform", `scale(${modalScale})`);
*/
        this._overviewModal.show();
      },

      onEnteringStatePreEndOfGame(){
        this.showOverview();
      },


      toggleControls(){
        dojo.toggleClass('layout-controls-container', 'layoutControlsHidden')

        // Hacking BGA framework
        if(dojo.hasClass("ebd-body", "mobile_version")){
          dojo.query(".player-board").forEach(elt => {
            if(elt.style.height != "auto"){
              dojo.style(elt, "min-height", elt.style.height);
              elt.style.height = "auto";
            }
          });
        }
      },


      getConfig(value, v){
        return localStorage.getItem(value) == null? v : localStorage.getItem(value);
      },

      setupSettings(){
        /*
        dojo.place($('preference_control_102').parentNode.parentNode, 'layout-controls-container');

        /*
         * Simple slider to show the zoom of scoresheet
        this._speedSlider = document.getElementById('layout-control-animation-speed');
        noUiSlider.create(this._speedSlider, {
          start: [100 - this._animationSpeed],
          step:10,
          padding:10,
          range: {
            'min': [0],
            'max': [100]
          },
        });
        this._speedSlider.noUiSlider.on('slide', (arg) => this.setAnimationSpeed(parseInt(arg[0])) );
        */
      },

/*
      setAnimationSpeed(speed){
        this._animationSpeed = 100 - speed;
        localStorage.setItem("alhambraAnimationSpeed", 100 - speed);
      },
*/


      /* This enable to inject translatable styled things to logs or action bar */
      /* @Override */
      format_string_recursive (log, args) {
        try {
          if (log && args && !args.processed) {
            args.processed = true;

            // Representation of the class of a card
            if (args.card_class !== undefined) {
              args.card_class = dojo.string.substitute("<span class='card-class-name class-${class}'>${name}</span>", {
                'name' : _(args.card_class),
                'class' : args.card_class_symbol,
              });


              if(args.card_class_symbol[0] == 6){
                // ROYAL_OFFER
                args.card_class_symbol = this.format_block('jstpl_coin', {
                  id:-1,
                  value : '+' + args.card_class_symbol[2],
                  type : 1
                });
              }
              else {
                args.card_class_symbol = dojo.string.substitute("<span class='card-class-symbol class-${class}'></span>", {'class' : args.card_class_symbol });
              }
            }

            if (args.card2_class !== undefined) {
              args.card2_class = dojo.string.substitute("<span class='card-class-name class-${class}'>${name}</span>", {
                'name' : _(args.card2_class),
                'class' : args.card2_class_symbol,
              });

              args.card2_class_symbol = dojo.string.substitute("<span class='card-class-symbol class-${class}'></span>", {'class' : args.card2_class_symbol });
            }



            // Coin icons
            var coinKeys = Object.keys(args).filter(key => key.substr(0,4) == 'coin' && key.substr(-4) != "type");
            coinKeys.forEach(key => {
              args[key] = this.format_block('jstpl_coin', {
                id:-1,
                value : args[key],
                type : args[key + '_type']
              });
              args[key + '_type'] = '';
            })
          }
        } catch (e) {
          console.error(log,args,"Exception thrown", e.stack);
        }

        return this.inherited(arguments);
      },

   });
});
