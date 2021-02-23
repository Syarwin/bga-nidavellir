define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  return declare("nidavellir.recruitTrait", null, {
    constructor(){
      this._notifications.push(
        ['recruit', 1000]
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
      }
    },

    onClickCardDiscard(card){
      debug(card);
    },
  });
});
