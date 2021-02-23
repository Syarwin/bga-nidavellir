define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  return declare("nidavellir.recruitTrait", null, {
    constructor(){
      this._notifications.push(
        ['recruit', 1000],
        ['discardCards', 1000],
      );
      this._activeStates.push("recruitDwarf");

      //this._selectedCoin = null;
      //this._tavernBids = [null, null, null];
    },

    onEnteringStateRecruitDwarf(args) {
      // activate the cards
      this.makeCardSelectable(args.cards, this.onClickCardRecruit.bind(this));
      dojo.addClass("tavern_" + args.tavern, "selectable");
    },

    onEnteringStateRecruitHero(args) {
      // activate the cards
      this.makeCardSelectable(args.cards, this.onClickCardRecruit.bind(this));
    },

    onClickCardRecruit(card){
      this.takeAction("recruit", { cardId : card.id });
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
        this.slide('card-' + card.id, card.location)
      }
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

    notif_discardCards(n){
      debug("Notif: discarding cards", n);
      this.slide('card-' + n.args.card.id, 'page-title', {
        duration:1000,
        destroy:true,
      });

      if(n.args.card2){
        this.slide('card-' + n.args.card2.id, 'page-title', {
          duration:1000,
          destroy:true,
        });
      }
    },
  });
});
