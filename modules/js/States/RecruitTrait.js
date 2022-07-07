define(['dojo', 'dojo/_base/declare'], (dojo, declare) => {
  const AUTOPICK = 102;
  const OFF = 1;
  const ON = 2;

  return declare('nidavellir.recruitTrait', null, {
    constructor() {
      this._notifications.push(
        ['recruitOrder', 500],
        ['recruitStart', 10],
        ['recruit', 1000],
        ['capture', 1000],
        ['denyCapture', 500],
        ['recruitHero', 2500],
        ['distinction', 2500],
        ['distinctionTie', 2500],
        ['returnCard', 1000],
        ['reserveCard', 800],
        ['exchangeCard', 1000],
        ['putBackCard', 1000],
      );
      this._activeStates.push('recruitDwarf');

      //this._selectedCoin = null;
      //this._tavernBids = [null, null, null];
    },

    notif_recruitOrder(n) {
      debug('Notif: new recruit order', n);
      this.gamedatas.order = n.args.order;
      this.updatePlayersOrder();
      this.gamedatas.orderIndex = 0;
      this.udpateInfoCounters();
    },

    notif_recruitStart(n) {
      debug('Notif: start recruiting', n);
      this.gamedatas.orderIndex = n.args.order - 1;
      this.udpateInfoCounters();
    },

    onEnteringStateRecruitDwarf(args) {
      if (!this.isCurrentPlayerActive()) return;
      // activate the cards
      this.makeCardSelectable(args.cards, this.onClickCardRecruit.bind(this));
      dojo.addClass('tavern_' + args.tavern, 'selectable');
      if (args.camp) {
        dojo.addClass('camp-container', 'selectable');
      }
      if (args.frigg) {
        this.addPrimaryActionButton('btnFrigg', _("Use Frigg's power"), () => {
          this.clientState('frigg', _('Select the card to put back under the deck'), args);
        });
      }
    },

    onEnteringStateRecruitCamp(args) {
      if (!this.isCurrentPlayerActive()) return;

      // activate the cards
      this.makeCardSelectable(args.cards, this.onClickCardRecruit.bind(this));
      dojo.addClass('camp-container', 'selectable');
    },

    onEnteringStateRecruitHero(args) {
      if (!this.isCurrentPlayerActive()) return;

      // activate the cards
      dojo.query('#hall .card').addClass('unselectable');
      this.makeCardSelectable(args.cards, this.onClickCardRecruit.bind(this));
      this.addPrimaryActionButton('btnShowHeroes', _('Show heroes'), () => this.openHeroesModal());
      dojo.addClass('tab-heroes', 'focus');
      this.openHeroesModal();
    },

    onClickCardRecruit(card, isId = false) {
      let cardId = isId ? card : card.id;
      let args = this.gamedatas.gamestate.args;
      if (args.capture && args.capture.includes(cardId)) {
        this.clientState('promptCapture', _("Do you want to capture the dwarf or lose the Giant's power ?"), {
          cardId,
        });
      } else {
        this.takeAction('recruit', { cardId, capture: false });
      }
    },

    onEnteringStatePromptCapture(args) {
      let cardId = args.cardId;
      $(`card-${cardId}`).classList.add('selected');
      this.addPrimaryActionButton('btnCapture', _('Capture'), () =>
        this.takeAction('recruit', { cardId, capture: true }),
      );
      this.addPrimaryActionButton('btnRecruit', _('Recruit'), () =>
        this.takeAction('recruit', { cardId, capture: false }),
      );
      this.addCancelStateBtn();
    },

    notif_recruit(n) {
      debug('Notif: new recruit', n);
      let card = n.args.card;

      // Remove loki token if needed
      if ($('card-' + card.id)) {
        let lokiToken = $('card-' + card.id).querySelector('#loki-token');
        if (lokiToken) {
          lokiToken.remove();
        }
      }

      if (card.class == 6) {
        // ROYAL OFFER
        this.slide('card-' + card.id, 'overall_player_board_' + n.args.player_id, {
          destroy: true,
        });
      } else {
        // DWARF AND THRUD
        if (!$('card-' + card.id))
          // Happens only for blacksmith distinction in principle
          this.addCard(card, 'tab-distinctions');
        this.slide('card-' + card.id, card.location);
      }
      dojo.attr('hero-line-' + n.args.player_id, 'data-n', n.args.line);

      if (card.class == 13) {
        // GIANT
        let tokenLocation = card.location.substr(0, card.location.length - 1) + card.giantClass;
        dojo.attr(tokenLocation, 'data-capture', '1');
      }
    },

    notif_denyCapture(n) {
      debug('Notif: deny capture', n);
      dojo.attr(n.args.card.location, 'data-capture', '0');
    },

    notif_capture(n) {
      debug('Notif: new capture', n);
      let card = n.args.card;
      let giant = n.args.giant;
      this.slide(`card-${card.id}`, card.location, 'before');

      let tokenLocation = card.location.substr(0, card.location.length - 1) + giant.giantClass;
      dojo.attr(tokenLocation, 'data-capture', '0');
    },

    notif_recruitHero(n) {
      debug('Notif: new hero recruit', n);
      let card = n.args.card;

      dojo.addClass('card-overlay', 'active');
      dojo.removeClass('card-' + card.id, 'selectable selected');
      if (this._heroesDialog.isDisplayed()) {
        this.slide('card-' + card.id, 'card-overlay');
        this._heroesDialog.hide();
      } else {
        this.slide('card-' + card.id, 'card-overlay', { from: 'tab-heroes' });
      }

      setTimeout(() => {
        dojo.removeClass('card-overlay', 'active');
        this.slide('card-' + card.id, card.location);
        dojo.attr('hero-line-' + n.args.player_id, 'data-n', n.args.line);
      }, 1500);
    },

    notif_distinction(n) {
      debug('Notif: distinction', n);
      let card = n.args.card;

      dojo.addClass('card-overlay', 'active');
      if (this._distinctionsDialog.isDisplayed()) {
        this.slide('card-' + card.id, 'card-overlay');
        this._distinctionsDialog.hide();
      } else {
        this.slide('card-' + card.id, 'card-overlay', { from: 'tab-distinctions' });
      }

      setTimeout(() => {
        dojo.removeClass('card-overlay', 'active');
        this.slide('card-' + card.id, card.location);
      }, 1500);
    },

    notif_distinctionTie(n) {
      debug('Notif: distinction', n);
      let card = n.args.card;

      dojo.addClass('card-overlay', 'active');
      if (this._distinctionsDialog.isDisplayed()) {
        this.slide('card-' + card.id, 'card-overlay');
        this._distinctionsDialog.hide();
      } else {
        this.slide('card-' + card.id, 'card-overlay', { from: 'tab-distinctions' });
      }

      setTimeout(() => {
        dojo.removeClass('card-overlay', 'active');
        this.slide('card-' + card.id, 'page-title', {
          destroy: true,
        });
      }, 1500);
    },

    onEnteringStateDiscardCard(args) {
      if (args.n == 1) {
        this.changePageTitle('single');
      }
      if (this.isCurrentPlayerActive()) {
        this.makeCardSelectable(args.cards, this.onClickCardDiscard.bind(this));
        this._selectedCardsForDiscard = [];
        this._amountToDiscard = args.n;

        if (args.thor) {
          this.addPrimaryActionButton('btnUseThor', _("Use Thor's power"), () =>
            this.takeAction('actUseThorPower', {}),
          );
        }
      }
    },

    onClickCardDiscard(card) {
      if (this._selectedCardsForDiscard.includes(card.id)) {
        // Already selected => unselect it
        dojo.removeClass('card-' + card.id, 'selected');
        this._selectedCardsForDiscard.splice(this._selectedCardsForDiscard.indexOf(card.id), 1);
      } else if (this._selectedCardsForDiscard.length < this._amountToDiscard) {
        // Select it
        dojo.addClass('card-' + card.id, 'selected');
        this._selectedCardsForDiscard.push(card.id);
      }

      dojo.destroy('btnConfirmDiscard');
      if (this._selectedCardsForDiscard.length == this._amountToDiscard) {
        this.addPrimaryActionButton('btnConfirmDiscard', _('Confirm discard'), () => this.onClickConfirmDiscard());
      }
    },

    onClickConfirmDiscard() {
      this.takeAction('discardCards', {
        cardIds: this._selectedCardsForDiscard.join(';'),
      });
    },

    onEnteringStateChooseThrudColumn(args) {
      if (this.isCurrentPlayerActive()) this.makeColumnsSelectable('thrud');
    },

    onEnteringStateChooseYludColumn(args) {
      if (this.isCurrentPlayerActive()) this.makeColumnsSelectable('ylud');
    },

    onEnteringStatePlaceOlwynDouble(args) {
      if (this.isCurrentPlayerActive()) this.makeColumnsSelectable('olwyn', args.columns);
    },

    makeColumnsSelectable(hero, columns = 0) {
      columns = columns || [1, 2, 3, 4, 5];
      columns.forEach((col) => {
        dojo.addClass('command-zone_' + this.player_id + '_' + col, 'selectable');
        this.connect($('command-zone_' + this.player_id + '_' + col), 'click', () => {
          this.takeAction('chooseColumn', {
            column: col,
            hero: hero,
          });
        });
      });
    },

    // THINGVELLIR 2 players special rule
    onEnteringStateDiscardTavernCard(args) {
      if (!this.isCurrentPlayerActive()) return;

      this.makeCardSelectable(args.cards, (card) => this.takeAction('discardTavern', { cardId: card.id }));
      dojo.addClass('tavern_' + args.tavern, 'selectable-discard');
    },

    /******************************
     *** THINGVELLIR MERCENARIES ***
     ******************************/
    onEnteringStateChooseEnlistOrder(args) {
      if (!this.isCurrentPlayerActive()) return;

      this.addPrimaryActionButton('btnOrderFirst', _('First'), () => this.takeAction('chooseOrder', { position: 0 }));
      this.addPrimaryActionButton('btnOrderLast', _('Last'), () => this.takeAction('chooseOrder', { position: 1 }));
    },

    onEnteringStateEnlistMercenary(args) {
      if (!this.isCurrentPlayerActive()) return;

      this._selectedMercenary = null;
      args.cards.forEach((cardId) => {
        if ($('card-' + cardId).parentNode.id != 'enlist-' + this.player_id)
          this.slide('card-' + cardId, 'enlist-' + this.player_id);
      });
      this.makeCardSelectable(args.cards, this.onClickCardEnlist.bind(this));

      [1, 2, 3, 4, 5].forEach((col) => {
        this.connect($('command-zone_' + this.player_id + '_' + col), 'click', () => this.onClickColumnEnlist(col));
      });
    },

    onClickCardEnlist(card) {
      if (this._selectedMercenary != null) {
        dojo.removeClass('card-' + this._selectedMercenary.id, 'selected');
        [1, 2, 3, 4, 5].forEach((col) => dojo.removeClass('command-zone_' + this.player_id + '_' + col, 'selectable'));
      }

      if (this._selectedMercenary != null && this._selectedMercenary.id == card.id) {
        this._selectedMercenary = null;
      } else {
        this._selectedMercenary = card;
        dojo.addClass('card-' + card.id, 'selected');
        Object.keys(card.grades).forEach((col) =>
          dojo.addClass('command-zone_' + this.player_id + '_' + col, 'selectable'),
        );
      }
    },

    onClickColumnEnlist(col) {
      if (this._selectedMercenary == null) return;

      if (!Object.keys(this._selectedMercenary.grades).map(Number).includes(col)) return;

      this.takeAction('enlist', {
        cardId: this._selectedMercenary.id,
        column: col,
      });
    },

    /**********************
     ******** HOFUD ********
     **********************/
    onEnteringStateDiscardHofud(args) {
      if (!this.isCurrentPlayerActive()) return;

      this._selectedWarrior = null;
      args._private.cards.forEach((cardId) => this.slide('card-' + cardId, 'enlist-' + this.player_id));
      this.makeCardSelectable(args._private.cards, this.onClickCardDiscardHofud.bind(this));
    },

    onClickCardDiscardHofud(card) {
      if (this._selectedWarrior != null) {
        dojo.removeClass('card-' + this._selectedWarrior, 'selected');
      }

      dojo.destroy('btnConfirmDiscardHofud');
      if (this._selectedWarrior != null && this._selectedWarrior == card.id) {
        this._selectedWarrior = null;
      } else {
        this._selectedWarrior = card.id;
        dojo.addClass('card-' + card.id, 'selected');
        this.addPrimaryActionButton('btnConfirmDiscardHofud', _('Confirm'), () => this.onClickConfirmDiscardHofud());
      }
    },

    onClickConfirmDiscardHofud() {
      this.takeAction('discardHofud', { cardId: this._selectedWarrior });
    },

    /*********************************
     ******** BRISINGAMENS EOG ********
     *********************************/
    onEnteringStateBrisingamensDiscard(args) {
      if (!this.isCurrentPlayerActive()) return;

      this.makeCardSelectable(args.cards, this.onClickCardDiscard.bind(this));
      this._selectedCardsForDiscard = [];
      this._amountToDiscard = 1;
    },

    /**********************
     ******** ODIN ********
     *********************/
    onEnteringStateOdin(args) {
      if (!this.isCurrentPlayerActive()) return;

      this._selectedHero = null;
      Object.keys(args.cards).forEach((cardId) => {
        this.addPrimaryActionButton(
          'btnReturn' + cardId,
          this.format_string_recursive(_('Return ${hero_name}'), { hero_name: args.cards[cardId] }),
          () => {
            if (this._selectedHero !== null) {
              $('btnReturn' + this._selectedHero).classList.remove('selected');
            }

            this._selectedHero = cardId;
            $('btnReturn' + cardId).classList.add('selected');
            this.gamedatas.gamestate.descriptionmyturn = _('Choose an available neutral hero');
            this.updatePageTitle();

            dojo.query('#hall .card').addClass('unselectable');
            this.makeCardSelectable(args.heroes, this.onClickCardExchangeOdin.bind(this));
            dojo.addClass('tab-heroes', 'focus');
            this.openHeroesModal();
          },
        );
      });

      this.addPrimaryActionButton('btnSkip', _("Don't use"), () => this.takeAction('actSkipOdinPower', {}));
    },

    onClickCardExchangeOdin(card, isId = false) {
      let cardId = isId ? card : card.id;
      this.takeAction('actUseOdinPower', {
        cardId: this._selectedHero,
        heroId: cardId,
      });
    },

    notif_returnCard(n) {
      debug('Notif: return card', n);
      let card = n.args.card;
      this.slide('card-' + card.id, card.location);
    },

    /**********************
     ******** LOKI ********
     *********************/
    onEnteringStateLoki(args) {
      if (!this.isCurrentPlayerActive()) return;

      this.makeCardSelectable(args.cards, this.onClickCardLoki.bind(this));
      this.addPrimaryActionButton('btnSkip', _("Don't use"), () => this.takeAction('actSkipLokiPower', {}));
    },

    onClickCardLoki(card, isId = false) {
      let cardId = isId ? card : card.id;
      this.takeAction('actUseLokiPower', {
        cardId: cardId,
      });
    },

    notif_reserveCard(n) {
      debug('Notif: reserve card', n);
      let card = n.args.card;
      dojo.place('<div id="loki-token"><div></div>LOKI</div>', 'card-' + card.id);
    },

    /**********************
     ******** FREYA ********
     *********************/

    onEnteringStateFreya(args) {
      if (!this.isCurrentPlayerActive()) return;

      this.makeCardSelectable(args.cards, this.onClickCardFreya.bind(this));
      this._selectedCardsForFreya = [];
      this.addPrimaryActionButton('btnSkip', _("Don't use"), () => this.takeAction('actSkipFreyaPower', {}));
    },

    onClickCardFreya(card) {
      if (this._selectedCardsForFreya.includes(card.id)) {
        // Already selected => unselect it
        dojo.removeClass('card-' + card.id, 'selected');
        this._selectedCardsForFreya.splice(this._selectedCardsForFreya.indexOf(card.id), 1);
      } else if (this._selectedCardsForFreya.length < 2) {
        // Select it
        dojo.addClass('card-' + card.id, 'selected');
        this._selectedCardsForFreya.push(card.id);
      }

      dojo.destroy('btnConfirmFreya');
      if (this._selectedCardsForFreya.length == 2) {
        this.addPrimaryActionButton('btnConfirmFreya', _('Confirm exchange'), () =>
          this.takeAction('actUseFreyaPower', {
            card1Id: this._selectedCardsForFreya[0],
            card2Id: this._selectedCardsForFreya[1],
          }),
        );
      }
    },

    notif_exchangeCard(n) {
      debug('Notif: exchange card', n);
      let card1 = $('card-' + n.args.card.id);
      let card2 = $('card-' + n.args.card2.id);
      let target1 = card2.parentNode;
      let target2 = card1.parentNode;
      this.slide(card1, target1);
      this.slide(card2, target2);
    },

    onEnteringStatePlaceGullinbursti(args) {
      if (this.isCurrentPlayerActive()) this.makeColumnsSelectable('gullinbursti');
    },

    /**********************
     ******** FRIGG ********
     *********************/
    onEnteringStateFrigg(args) {
      this.addCancelStateBtn();
      this.makeCardSelectable(args.cards, this.onClickCardFrigg.bind(this));
    },

    onClickCardFrigg(card) {
      this.takeAction('actUseFriggPower', {
        cardId: card.id,
      });
    },

    notif_putBackCard(n) {
      debug('Notif: put back card', n);
      let card = $('card-' + n.args.card.id);
      this.slideToRightAndDestroy(card, false);
    },

    onEnteringStateFriggPick(args){
      this._distinctionExplorerCards = args.cardsObj;
      this.addPrimaryActionButton('btnShowDistinctionExplorerCards', _('Show cards'), () =>
        this.openDistinctionExplorerModal(),
      );
      this.openDistinctionExplorerModal();
    }
  });
});
