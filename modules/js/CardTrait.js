define(['dojo', 'dojo/_base/declare'], (dojo, declare) => {
  return declare('nidavellir.cardTrait', null, {
    constructor() {
      this._notifications.push(['discardCards', 1000], ['discardHofud', 1000], ['increaseForce', 800]);
      this._callbackOnCard = null;
      this._selectableCards = [];
      this._distinctionExplorerCards = null;
    },

    setupCards() {
      this.gamedatas.cards.taverns.forEach((card) => this.addCard(card, card.location));

      this.setupHall();
      this.setupDistinctions();
      this.setupDiscard();
      if (this.gamedatas.thingvellir) this.setupCamp();
      dojo.place("<div id='card-overlay'></div>", 'ebd-body');
    },

    setupHall() {
      this._heroesDialog = new customgame.modal('showHeroes', {
        class: 'nidavellir_popin',
        closeIcon: 'fa-times',
        openAnimation: true,
        openAnimationTarget: 'tab-heroes',
        contents: jstpl_heroesModal,
        closeAction: 'hide',
        statusElt: 'tab-heroes',
        verticalAlign: 'flex-start',

        //        scale:0.9,
        //        breakpoint:750,
      });

      this.gamedatas.cards.hall.forEach((card) => this.addCard(card, card.location));
      dojo.connect($('tab-heroes'), 'click', () => this.openHeroesModal());
    },

    openHeroesModal() {
      this._heroesDialog.show();
    },

    setupDistinctions() {
      this._distinctionsDialog = new customgame.modal('showDistinctions', {
        class: 'nidavellir_popin',
        closeIcon: 'fa-times',
        openAnimation: true,
        openAnimationTarget: 'tab-distinctions',
        contents: jstpl_distinctionsModal,
        closeAction: 'hide',
        statusElt: 'tab-distinctions',

        scale: 0.85,
        breakpoint: 420,
      });

      this.gamedatas.cards.evaluation.forEach((card) => this.addCard(card, card.location));
      dojo.connect($('tab-distinctions'), 'click', () => this.openDistinctionModal());
    },

    openDistinctionModal() {
      this._distinctionsDialog.show();
    },

    setupDiscard() {
      this._discardDialog = new customgame.modal('discard', {
        class: 'nidavellir_popin',
        closeIcon: 'fa-times',
        openAnimation: true,
        openAnimationTarget: 'tab-discard',
        closeAction: 'hide',
        statusElt: 'tab-discard',
        verticalAlign: 'flex-start',
      });

      this.gamedatas.cards.discard.forEach((card) => this.addCard(card, 'popin_discard_contents'));
      dojo.connect($('tab-discard'), 'click', () => this.openDiscardModal());
    },

    openDiscardModal() {
      this._discardDialog.show();
    },

    setupCamp() {
      this.gamedatas.cards.camp.forEach((card) => this.addCard(card, card.location));
    },

    addCard(card, container, animation = false) {
      card.parity = card.id % 2;
      card.ranks = card.grade.length;
      card.title = card.name ? _(card.name) : '';
      card.subtitle = card.subname ? _(card.subname) : '';
      card.desc = card.tooltip ? card.tooltip.map((o) => _(o)).join('<br />') : '';
      card.offer = card.class == 6 ? card.grade[0] : ''; // ROYAL OFFERING
      card.gradeHtml = card.grade.map((r) => this.getRankHtml(r)).join('');
      if(card.class == 14){// VALKYRIE
        card.gradeHtml = card.forces.map((r) => `<div class='valkyrie-force'>${r}</div>`).join('');        
      }

      this.place('jstpl_card', card, container);
      dojo.connect($('card-' + card.id), 'click', () => this.onClickCard(card));
      this.addTooltipHtml('card-' + card.id, this.format_block('jstpl_cardTooltip', card));

      if (card.class == 13 && card.flag == 0 && card.location.substr(0, 12) == 'command-zone') {
        // GIANT
        let tokenLocation = card.location.substr(0, card.location.length - 1) + card.giantClass;
        dojo.attr(tokenLocation, 'data-capture', '1');
      }

      if (animation) this.slideFromLeft('card-' + card.id);
    },

    getRankHtml(rank) {
      return this.format_block('jstpl_rank', { rank: rank != null ? rank : '' });
    },

    onClickCard(card) {
      if (this._selectableCards.includes(card.id)) {
        this._callbackOnCard(card);
      }
    },

    makeCardSelectable(cards, callback) {
      this._selectableCards = cards;
      this._callbackOnCard = callback;

      dojo.query('.card').removeClass('selectable');
      cards.forEach((cardId) => {
        dojo.removeClass('card-' + cardId, 'unselectable');
        dojo.addClass('card-' + cardId, 'selectable');
      });
    },

    slideFromLeft(elem) {
      elem = typeof elem == 'string' ? $(elem) : elem;
      let x = elem.offsetWidth + elem.offsetLeft + 30;
      dojo.addClass(elem, 'notransition');
      dojo.style(elem, 'opacity', '0');
      dojo.style(elem, 'left', -x + 'px');
      elem.offsetHeight;
      dojo.removeClass(elem, 'notransition');

      dojo.style(elem, 'opacity', '1');
      dojo.style(elem, 'left', '0px');
    },

    slideToRightAndDestroy(elem, toDiscard = true) {
      elem = typeof elem == 'string' ? $(elem) : elem;
      let stack = elem.parentNode;
      let x = stack.offsetWidth - elem.offsetLeft + 100;
      dojo.style(elem, 'left', x + 'px');
      setTimeout(() => {
        if (toDiscard) {
          dojo.place(elem, 'popin_discard_contents');
          dojo.style(elem, 'left', '0px');
        } else dojo.destroy(elem);
      }, 1000);
    },

    slideToDiscard(id) {
      this.slide('card-' + id, 'tab-discard', {
        duration: 1000,
      }).then(() => dojo.place('card-' + id, 'popin_discard_contents'));
    },

    notif_discardCards(n) {
      debug('Notif: discarding cards', n);

      if (!$('card-' + n.args.card.id)) {
        // Special case of explorer distinction
        this.addCard(n.args.card, 'tab-distinctions');
      }

      this.slideToDiscard(n.args.card.id);
      if (n.args.card2) {
        this.slideToDiscard(n.args.card2.id);
      }
    },

    notif_discardHofud(n) {
      debug('Notif: discarding card for Hofud', n);

      this.slideToDiscard(n.args.card.id);

      if (this.player_id == n.args.player_id) {
        n.args.warriors.forEach((cardId) => this.slide('card-' + cardId, 'command-zone_' + this.player_id + '_5'));
      }
    },

    notif_increaseForce(n) {
      debug('Notif: increase Valkyrie force', n);
      let cardId = `card-${n.args.valkyrieId}`;
      $(cardId).dataset.flag = n.args.force;

      let content = this.tooltips[cardId].label;
      content = content.replace(/data-flag="[0-9]*"/g, `data-flag="${n.args.force}"`);
      this.tooltips[cardId].label = content;
    },

    /*********************************
     ****** EXPLORER DISTINCTION ******
     *********************************/
    onEnteringStateDistinctionExplorer(args) {
      if (!this.isCurrentPlayerActive()) return;

      this._distinctionExplorerCards = args.cardsObj;
      this.addPrimaryActionButton('btnShowDistinctionExplorerCards', _('Show cards'), () =>
        this.openDistinctionExplorerModal(),
      );
      this.openDistinctionExplorerModal();
    },

    openDistinctionExplorerModal() {
      this._distinctionExplorerModal = new customgame.modal('distinctionExplorer', {
        class: 'nidavellir_popin',
        closeIcon: 'fa-times',
      });

      this._distinctionExplorerCards.forEach((card) => this.addCard(card, 'popin_distinctionExplorer_contents'));
      this.makeCardSelectable(
        this._distinctionExplorerCards.map((card) => card.id),
        this.onClickCardRecruit.bind(this),
      );
      this._distinctionExplorerModal.show();
    },

    /********************
     ****** ANDUMIA ******
     ********************/
    onEnteringStatePickDiscardAndumia(args) {
      if (!this.isCurrentPlayerActive()) return;

      this.makeCardSelectable(args.cards, this.onClickCardRecruit.bind(this));
      this.openDiscardModal();
    },

    onEnteringStateBrisingamens(args) {
      if (!this.isCurrentPlayerActive()) return;

      this.makeCardSelectable(args.cards, this.onClickCardRecruit.bind(this));
      this.openDiscardModal();
    },
  });
});
