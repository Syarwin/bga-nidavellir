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
      this.makeCardSelectable(args.cards, this.onClickRecruitDwarf.bind(this));
      dojo.addClass("tavern_" + args.tavern, "selectable");
    },

    onClickRecruitDwarf(card){
      this.takeAction("recruitDwarf", { cardId : card.id });
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
