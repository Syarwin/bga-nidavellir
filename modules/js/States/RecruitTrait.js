define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  return declare("nidavellir.recruitTrait", null, {
    constructor(){
      this._notifications.push(
        //['playerBid', 500],
        //['revealBids', 800],
        //['recruitStart', 500]
      );

      //this._selectedCoin = null;
      //this._tavernBids = [null, null, null];
    },
    
    onEnteringStateRecruitDwarf(args) {
        debug("Recruit Dwarf" + args);
        if(this.isCurrentPlayerActive()){
            // activate the cards
            this.makeCardSelectable(args.tavern, this.onClickRecruitDwarf.bind(this));
            dojo.query("#tavern_" + args.tavern + " .card").forEach(el => this.connect(el, "click", () =>  this.onClickRecruitDwarf() ));
        }
    },
    
    onClickRecruitDwarf(event) {
        console.log("toto" + event);
    },
  });
});