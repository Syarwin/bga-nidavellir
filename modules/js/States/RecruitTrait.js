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
    },

    onClickRecruitDwarf(card){
      this.takeAction("recruitDwarf", { cardId : card.id });
    },


    notif_recruit(n){
      debug("Notif: new recruit", n);
      // TODO 
    },
  });
});
