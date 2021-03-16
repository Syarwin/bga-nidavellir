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


    makeColumnsSelectable(hero){
      [1,2,3,4,5].forEach(col => {
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
      dojo.addClass("tavern_" + args.tavern, "selectable");
    },
  });
});
