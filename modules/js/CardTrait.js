define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  return declare("nidavellir.cardTrait", null, {
    constructor(){
      /*
      this._notifications.push(
        ['newHand', 100],
        ['giveCard', 1000],
        ['receiveCard', 1000]
      );
      */
      this._callbackOnCard = null;
      this._selectableCards = [];
    },

    setupTaverns(){
      this.gamedatas.taverns.forEach(card => this.addCard(card, card.location));
    },

    addCard(card, container){
      card.parity = card.id % 2;
      this.place('jstpl_card', card, container);
      card.grade.forEach(rank => this.addRank(rank, card) );
      dojo.connect($("card-" + card.id), "click", () => this.onClickCard(card) );
    },

    addRank(rank, card){
      this.place('jstpl_rank', { rank : rank ?? '' }, 'card-grade-' + card.id);
    },


    onClickCard(card){
      if(this._selectableCards.includes(card.id)){
        this._callbackOnCard(card);
      }
    },

    makeCardSelectable(cards, callback){
      this._selectableCards = cards;
      this._callbackOnCard = callback;

      dojo.query(".card").removeClass("selectable");
      cards.forEach(cardId => dojo.addClass('card-' + cardId, "selectable") );
    },
  });
});
