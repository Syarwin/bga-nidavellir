define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  return declare("nidavellir.cardTrait", null, {
    constructor(){
      /*
      this._notifications.push(
        ['newHand', 100],
        ['giveCard', 1000],
        ['receiveCard', 1000]
      );
      this._callbackOnCard = null;
      this._selectableCards = [];
      */
    },

    setupTaverns(){
      this.gamedatas.taverns.forEach(card => this.addCard(card, card.location));
    },

    addCard(card, container){
      card.parity = card.id % 2;
      this.place('jstpl_card', card, container);
      card.grade.forEach(rank => this.addRank(rank, card) );
    },

    addRank(rank, card){
      this.place('jstpl_rank', { rank : rank ?? '' }, 'card-grade-' + card.id);
    },
  });
});
