define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  const AUTOPICK = 102;
  const OFF = 1;
  const ON = 2;

  return declare("nidavellir.recruitTrait", null, {
    constructor(){
      this._notifications.push(
        ['recruitOrder', 500],
        ['recruitStart', 10],
        ['recruit', 1000],
        ['recruitHero', 2500],
        ['distinction', 2500],
        ['distinctionTie', 2500]
      );
      this._activeStates.push("recruitDwarf");

      //this._selectedCoin = null;
      //this._tavernBids = [null, null, null];
    },

    notif_recruitOrder(n){
      debug("Notif: new recruit order", n);
      this.gamedatas.order = n.args.order;
      this.updatePlayersOrder();
      this.gamedatas.orderIndex = 0;
      this.udpateInfoCounters();
    },

    notif_recruitStart(n){
      debug("Notif: start recruiting", n);
      this.gamedatas.orderIndex = n.args.order - 1;
      this.udpateInfoCounters();
    },

    onEnteringStateRecruitDwarf(args) {
      if(!this.isCurrentPlayerActive())
        return;
      // activate the cards
      this.makeCardSelectable(args.cards, this.onClickCardRecruit.bind(this));
      dojo.addClass("tavern_" + args.tavern, "selectable");
      if(args.camp){
        dojo.addClass('camp-container', "selectable");
      }
    },

    onEnteringStateRecruitCamp(args) {
      if(!this.isCurrentPlayerActive())
        return;

      // activate the cards
      this.makeCardSelectable(args.cards, this.onClickCardRecruit.bind(this));
      dojo.addClass('camp-container', "selectable");
    },


    onEnteringStateRecruitHero(args) {
      if(!this.isCurrentPlayerActive())
        return;

      // activate the cards
      dojo.query("#hall .card").addClass("unselectable");
      this.makeCardSelectable(args.cards, this.onClickCardRecruit.bind(this));
      this.addPrimaryActionButton("btnShowHeroes", _("Show heroes"), () => this.openHeroesModal() );
      dojo.addClass('tab-heroes', "focus");
      this.openHeroesModal();
    },

    onClickCardRecruit(card, isId = false){
      this.takeAction("recruit", { cardId : isId ? card : card.id });
    },


    notif_recruit(n){
      debug("Notif: new recruit", n);
      let card = n.args.card;

      if(card.class == 6){
        // ROYAL OFFER
        this.slide('card-' + card.id, "overall_player_board_" + n.args.player_id, {
          destroy:true,
        });
      } else {
        // DWARF AND THRUD
        if(!$('card-' + card.id)) // Happens only for blacksmith distinction in principle
          this.addCard(card, 'tab-distinctions');
        this.slide('card-' + card.id, card.location)
      }
      dojo.attr('hero-line-' + n.args.player_id, 'data-n', n.args.line);
    },



    notif_recruitHero(n){
      debug("Notif: new hero recruit", n);
      let card = n.args.card;

      dojo.addClass("card-overlay", "active");
      dojo.removeClass("card-" + card.id, "selectable selected");
      if(this._heroesDialog.isDisplayed()){
        this.slide("card-" + card.id, "card-overlay");
        this._heroesDialog.hide();
      } else {
        this.slide("card-" + card.id, "card-overlay", { from : "tab-heroes" });
      }

      setTimeout(() => {
        dojo.removeClass("card-overlay", "active");
        this.slide('card-' + card.id, card.location);
        dojo.attr('hero-line-' + n.args.player_id, 'data-n', n.args.line);
      }, 1500);
    },


    notif_distinction(n){
      debug("Notif: distinction", n);
      let card = n.args.card;

      dojo.addClass("card-overlay", "active");
      if(this._distinctionsDialog.isDisplayed()){
        this.slide("card-" + card.id, "card-overlay");
        this._distinctionsDialog.hide();
      } else {
        this.slide("card-" + card.id, "card-overlay", { from : "tab-distinctions" });
      }

      setTimeout(() => {
        dojo.removeClass("card-overlay", "active");
        this.slide('card-' + card.id, card.location);
      }, 1500);
    },

    notif_distinctionTie(n){
      debug("Notif: distinction", n);
      let card = n.args.card;

      dojo.addClass("card-overlay", "active");
      if(this._distinctionsDialog.isDisplayed()){
        this.slide("card-" + card.id, "card-overlay");
        this._distinctionsDialog.hide();
      } else {
        this.slide("card-" + card.id, "card-overlay", { from : "tab-distinctions" });
      }

      setTimeout(() => {
        dojo.removeClass("card-overlay", "active");
        this.slide('card-' + card.id, 'page-title', {
          destroy:true,
        });
      }, 1500);
    },



    onEnteringStateDiscardCard(args){
      if(args.n == 1){
        this.changePageTitle("single");
      }
      if(this.isCurrentPlayerActive()){
        this.makeCardSelectable(args.cards, this.onClickCardDiscard.bind(this));
        this._selectedCardsForDiscard = [];
        this._amountToDiscard = args.n;
      }
    },

    onClickCardDiscard(card){
      if(this._selectedCardsForDiscard.includes(card.id)){
        // Already selected => unselect it
        dojo.removeClass("card-" + card.id, "selected");
        this._selectedCardsForDiscard.splice(this._selectedCardsForDiscard.indexOf(card.id), 1)
      } else if(this._selectedCardsForDiscard.length < this._amountToDiscard){
        // Select it
        dojo.addClass("card-" + card.id, "selected");
        this._selectedCardsForDiscard.push(card.id);
      }

      dojo.destroy("btnConfirmDiscard");
      if(this._selectedCardsForDiscard.length == this._amountToDiscard){
        this.addPrimaryActionButton("btnConfirmDiscard", _("Confirm discard"), () => this.onClickConfirmDiscard())
      }
    },

    onClickConfirmDiscard(){
      this.takeAction('discardCards', {
        cardIds: this._selectedCardsForDiscard.join(';'),
      });
    },



    onEnteringStateChooseThrudColumn(args){
      if(this.isCurrentPlayerActive())
        this.makeColumnsSelectable('thrud');
    },


    onEnteringStateChooseYludColumn(args){
      if(this.isCurrentPlayerActive())
        this.makeColumnsSelectable('ylud');
    },

    onEnteringStatePlaceOlwynDouble(args){
      if(this.isCurrentPlayerActive())
        this.makeColumnsSelectable('olwyn', args.columns);
    },


    makeColumnsSelectable(hero, columns = 0){
      columns = columns || [1,2,3,4,5];
      columns.forEach(col => {
        dojo.addClass('command-zone_' + this.player_id + '_' + col, "selectable");
        this.connect($('command-zone_' + this.player_id + '_' + col), 'click', () => {
          this.takeAction('chooseColumn', {
            column:col,
            hero:hero,
          })
        });
      });
    },



    // THINGVELLIR 2 players special rule
    onEnteringStateDiscardTavernCard(args){
      if(!this.isCurrentPlayerActive())
        return;

      this.makeCardSelectable(args.cards, (card) => this.takeAction('discardTavern', { cardId: card.id }) );
      dojo.addClass("tavern_" + args.tavern, "selectable-discard");
    },


    /******************************
    *** THINGVELLIR MERCENARIES ***
    ******************************/
    onEnteringStateChooseEnlistOrder(args){
      if(!this.isCurrentPlayerActive())
        return;

      this.addPrimaryActionButton("btnOrderFirst", _("First"), () => this.takeAction("chooseOrder", { position:0 }));
      this.addPrimaryActionButton("btnOrderLast", _("Last"), () => this.takeAction("chooseOrder", { position:1 }));
    },


    onEnteringStateEnlistMercenary(args){
      if(!this.isCurrentPlayerActive())
        return;

      this._selectedMercenary = null;
      args.cards.forEach(cardId => {
        if($('card-' + cardId).parentNode.id != 'enlist-' + this.player_id)
          this.slide('card-' + cardId, 'enlist-' + this.player_id)
      });
      this.makeCardSelectable(args.cards, this.onClickCardEnlist.bind(this) );

      [1,2,3,4,5].forEach(col => {
        this.connect($('command-zone_' + this.player_id + '_' + col), 'click', () => this.onClickColumnEnlist(col) );
      });
    },

    onClickCardEnlist(card){
      if(this._selectedMercenary != null){
        dojo.removeClass("card-" + this._selectedMercenary.id, "selected");
        [1,2,3,4,5].forEach(col => dojo.removeClass('command-zone_' + this.player_id + '_' + col, "selectable") );
      }

      if(this._selectedMercenary != null && this._selectedMercenary.id == card.id){
        this._selectedMercenary = null;
      }
      else {
        this._selectedMercenary = card;
        dojo.addClass('card-' + card.id, "selected");
        Object.keys(card.grades).forEach(col => dojo.addClass('command-zone_' + this.player_id + '_' + col, "selectable") )
      }
    },

    onClickColumnEnlist(col){
      if(this._selectedMercenary == null)
        return;

      if(!Object.keys(this._selectedMercenary.grades).map(Number).includes(col))
        return;

      this.takeAction('enlist', {
        cardId:this._selectedMercenary.id,
        column: col,
      });
    },



    /**********************
    ******** HOFUD ********
    **********************/
    onEnteringStateDiscardHofud(args){
      if(!this.isCurrentPlayerActive())
        return;

      this._selectedWarrior = null;
      args._private.cards.forEach(cardId => this.slide('card-' + cardId, 'enlist-' + this.player_id) );
      this.makeCardSelectable(args._private.cards, this.onClickCardDiscardHofud.bind(this) );
    },

    onClickCardDiscardHofud(card){
      if(this._selectedWarrior != null){
        dojo.removeClass("card-" + this._selectedWarrior, "selected");
      }

      dojo.destroy("btnConfirmDiscardHofud");
      if(this._selectedWarrior != null && this._selectedWarrior == card.id){
        this._selectedWarrior = null;
      }
      else {
        this._selectedWarrior = card.id;
        dojo.addClass('card-' + card.id, "selected");
        this.addPrimaryActionButton("btnConfirmDiscardHofud", _("Confirm"), () => this.onClickConfirmDiscardHofud());
      }
    },

    onClickConfirmDiscardHofud(){
      this.takeAction('discardHofud', {cardId: this._selectedWarrior });
    },


    /*********************************
    ******** BRISINGAMENS EOG ********
    *********************************/
    onEnteringStateBrisingamensDiscard(args){
      if(!this.isCurrentPlayerActive())
        return;

      this.makeCardSelectable(args.cards, this.onClickCardDiscard.bind(this));
      this._selectedCardsForDiscard = [];
      this._amountToDiscard = 1;
    },

  });
});
