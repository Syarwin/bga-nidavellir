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
        dojo.query(".tavern-cards-holder").removeClass("selectable selectable-discard");
        dojo.query(".card").removeClass("selectable unselectable selected");
        dojo.query(".nid-tab").removeClass("focus");
        dojo.query('.command-zone-container .cards-class').removeClass("selectable");
        dojo.removeClass("camp-container", "selectable");
        if(this._distinctionExplorerCards != null)
          this._distinctionExplorerModal.destroy();
        if(this._discardModal != null)
          this._discardModal.destroy();

        this.inherited(arguments);
      },

      onUpdateActionButtons(){
        if(isDebug){
          this.addPrimaryActionButton('btnTest', 'Autobid', () => this.takeAction('autobid') );
          this.addPrimaryActionButton('btnPass', 'Pass', () => this.takeAction('pass') );
        }

        this.inherited(arguments);
      },


      setupInfoPanel(){
        this.turnCounter = new ebg.counter();
        this.turnCounter.create('turn-counter');

        this.place('jstpl_configPlayerBoard', {
          autopick:_('Autopick last card'),
          enabled:_('Enabled'),
          disabled:_('Disabled'),
        }, 'player_boards', 'first');
        dojo.connect($('show-settings'), 'onclick', () => this.toggleControls() );
        this.addTooltip( 'show-settings', '', _("Display some settings about the game."));

        if(this.isReadOnly()){
          dojo.destroy("autopick-selector");
        } else {
          $('autopick').value = this.gamedatas.players[this.player_id].autopick? 1 : 0;
          dojo.connect($('autopick'), 'change', () => {
            this.ajaxcall("/nidavellir/nidavellir/setAutopick.html", { autopick: $("autopick").value });
          });
        }

        this.setupSettings();
        this.setupHelper();
      },

      udpateInfoCounters(){
        dojo.attr('age-counter', 'data-value', this.gamedatas.age);
        this.turnCounter.toValue(this.gamedatas.turn);
        dojo.attr('ebd-body', 'data-tavern', this.gamedatas.tavern);
        dojo.attr('ebd-body', 'data-order-index', this.gamedatas.orderIndex);
        dojo.attr('ebd-body', 'data-age', this.gamedatas.age);
        dojo.toggleClass('tab-distinctions', 'hidden', this.gamedatas.age == 2);
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
          scale:0.8,
          breakpoint:800,
        });

        for(var pId in this.gamedatas.players){
          let player = this.gamedatas.players[pId];

          dojo.place('<th>' + player.name + '</th>', 'overview-headers');
          for(var i = 0; i < 8; i++){
            dojo.place('<td id="overview-' + pId + '-'+ i + '"></td>', "overview-row-" + i);
          }
          dojo.place('<td id="overview-' + pId + '-total"></td>', "overview-total");
        }

        dojo.connect($('tab-score'), 'onclick', () => this.showOverview() );
      },

      showOverview(){
        debug("Showing overview:");
        this._overviewModal.show();
      },

      onEnteringStateGameEnd(){
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
        this._layoutMode = this.getConfig("NidavellirLayout", "normal");
        this.setLayoutMode(this._layoutMode);
        dojo.connect($('layout-normal'), 'click', () => this.setLayoutMode('normal'));
        dojo.connect($('layout-compact'), 'click', () => this.setLayoutMode('compact'));
      },

      setLayoutMode(layout){
        this._layoutMode = layout;
        dojo.attr("ebd-body", "data-mode", layout);
        localStorage.setItem("NidavellirLayout", layout);
      },




      /*
       * Display a helper with global scoring
       */
      setupHelper(){
        let contents = this.format_block('jstpl_helpModal', {
          warriors: _("Warriors"),
          hunters: _("Hunters"),
          miners: _("Miners"),
          blacksmith: _("Blacksmith"),
          explorers: _("Explorers"),

          warriorsText: _("Their Bravery Value is equal to the sum of their Bravery Points, to which the Elvaland who gets majority in ranks in the Warrior column, add his coin of highest value."),
          warriorsText2: _("In case of a tie, all tied Elvalands add their highest value coin to their Warrior Bravery Value."),
          hunterText: _("Their Bravery Value is equal to the number of Hunters squared."),
          minersText: _("Their Bravery Value is equal to the sum of their Bravery Points multiplied by the number of ranks in their column."),
          blacksmithText:_("Their Bravery Value is a mathematical sequence (+3, +4, +5, +6, ...)."),
          explorerText:_("Their Bravery Value is equal to the sum of their Bravery Points."),
        });

        this._helperModal = new customgame.modal("showHelpsheet", {
          class:"nidavellir_popin",
          closeIcon:'fa-times',
          openAnimation:true,
          openAnimationTarget:"show-help",
          contents:contents,
          closeAction:'hide',
          verticalAlign:'flex-start',
        });


        let grades = [
          '',
          ['', ''],
          ['', ''],
          [5, 8],
          [0, 0],
          [5, 4],
        ];

        for(var i = 1; i <= 5; i++){
          let card = {
            id:-1,
            parity:0,
            ranks:1,
            class:i,
            gradeHtml: '<div class="card-rank">' + grades[i][0] + '</div>',
          };

          this.place('jstpl_card', card, 'helpers-cards-' + i);

          card.parity = 1;
          card.gradeHtml =  '<div class="card-rank">' + grades[i][1] + '</div>';
          this.place('jstpl_card', card, 'helpers-cards-' + i);
        }

        dojo.connect($('show-help'), 'onclick', () => this.showHelper() );
      },

      showHelper(){
        debug("Showing overview:");
        this._helperModal.show();
      },


      /////////////////////////
      /////////////////////////
      /////////   LOG   ///////
      /////////////////////////
      /////////////////////////

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
