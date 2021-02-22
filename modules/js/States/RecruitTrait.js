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
      this.slide('card-' + card.id, card.location, {
        clearPos: true
      })
    },

  });
});
