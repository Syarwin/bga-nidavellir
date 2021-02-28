{OVERALL_GAME_HEADER}

<div id="nidavellir-board">
  <div id="exterior-wrapper">
    <div id="exterior">
      <div id="tabs-container">
        <div class="nid-tab" id="tab-camp"></div>
        <div class="nid-tab" id="tab-heroes"></div>
        <div class="nid-tab" id="tab-distinctions"></div>
      </div>

      <div id="treasure-container">
        <div id="treasure"></div>
        <div class="treasure-decoration" id="treasure-decoration-1"></div>
        <div class="treasure-decoration" id="treasure-decoration-2"></div>
        <div class="treasure-decoration" id="treasure-decoration-3"></div>
      </div>


      <div id="taverns-container">
        <div id="taverns-pre-bids-zone"></div>

        <div id="taverns">

          <div class="tavern-sign-holder">
            <div class="tavern-mount"></div>
            <div class="tavern-sign" id="tavern-sign-0">
              <div class="bids-drop-zone" id="bids-drop-zone-0"></div>
              <div class="tavern-coin-holder" id="tavern-coin-holder-0"></div>
            </div>
          </div>
          <div class="tavern-cards-wrapper">
            <div class="tavern-cards-holder" id="tavern_0"></div>
          </div>


          <div class="tavern-sign-holder">
            <div class="tavern-mount"></div>
            <div class="tavern-sign" id="tavern-sign-1">
              <div class="bids-drop-zone" id="bids-drop-zone-1"></div>
              <div class="tavern-coin-holder" id="tavern-coin-holder-1"></div>
            </div>
          </div>
          <div class="tavern-cards-wrapper">
            <div class="tavern-cards-holder" id="tavern_1"></div>
          </div>


          <div class="tavern-sign-holder">
            <div class="tavern-mount"></div>
            <div class="tavern-sign" id="tavern-sign-2">
              <div class="bids-drop-zone" id="bids-drop-zone-2"></div>
              <div class="tavern-coin-holder" id="tavern-coin-holder-2"></div>
            </div>
          </div>
          <div class="tavern-cards-wrapper">
            <div class="tavern-cards-holder" id="tavern_2"></div>
          </div>


        </div>
      </div>
    </div>
  </div>

  <div id="player-boards"></div>
</div>

<script type="text/javascript">
var jstpl_playerPanel = `
<div class="panel-container" id="panel-container-\${id}" data-no="\${no}">
  <div class="coins-zone" id="coins-zone-\${id}"></div>
  <div class="bids-zone" id="bids-zone-\${id}">
    <div class="bids-zone-tavern-holder tavern-0">
      <div class="bids-zone-tavern" id="bids-zone-0-\${id}"></div>
    </div>
    <div class="bids-zone-tavern-holder tavern-1">
      <div class="bids-zone-tavern" id="bids-zone-1-\${id}"></div>
    </div>
    <div class="bids-zone-tavern-holder tavern-2">
      <div class="bids-zone-tavern" id="bids-zone-2-\${id}"></div>
    </div>
  </div>
  <div class="command-zone" id="command-zone-\${id}">
    <div id="command-zone-overview-\${id}-1" class="command-zone-class" data-class="1"></div>
    <div id="command-zone-overview-\${id}-2" class="command-zone-class" data-class="2"></div>
    <div id="command-zone-overview-\${id}-3" class="command-zone-class" data-class="3"></div>
    <div id="command-zone-overview-\${id}-4" class="command-zone-class" data-class="4"></div>
    <div id="command-zone-overview-\${id}-5" class="command-zone-class" data-class="5"></div>
  </div>
</div>`;

var jstpl_gemContainer = `
<div id="gem-container-\${id}" class="gem-container"></div>
`;

var jstpl_gem = `
<div id="gem-\${value}" data-value="\${value}" class="gem"></div>
`;



var jstpl_coin = `
<div class="coin" id="coin-\${id}" data-type="\${type}" data-value="\${value}">
  <div class="coin-holder">
    <div class="coin-inner">
      <div class="coin-value">\${value}</div>
    </div>
  </div>
</div>
`;


var jstpl_card = `
<div class="card" id="card-\${id}" data-id="\${id}" data-class="\${class}" data-parity="\${parity}" data-ranks="\${ranks}">
  <div class="card-grade" id="card-grade-\${id}">
    <div class="card-class-icon"></div>
  </div>
</div>
`;

var jstpl_rank = `
  <div class="card-rank">\${rank}</div>
`;

var jstpl_playerBoard = `
<div id="player-board-\${no}" data-color="\${color}" class="nidavellir-player-board">
  <div class="cards-class" id="command-zone_\${id}_0"></div>
  <div class="cards-class" id="command-zone_\${id}_1"></div>
  <div class="cards-class" id="command-zone_\${id}_2"></div>
  <div class="cards-class" id="command-zone_\${id}_3"></div>
  <div class="cards-class" id="command-zone_\${id}_4"></div>
  <div class="cards-class" id="command-zone_\${id}_5"></div>
  <div class="cards-class" id="command-zone_\${id}_6"></div>
</div>
`



var jstpl_configPlayerBoard = `
<div class='player-board' id="player_board_config">
  <div id="player_config" class="player_board_content">
    <div id="player_info_row">
      <div id="age-counter-holder">
        \${age}
        <span id="age-counter"></span>
      </div>

      <div id="turn-counter-holder">
        \${turn}
        <span id="turn-counter"></span>
      </div>
    </div>

    <div id="player_config_row">
      <div id="show-overview">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
          <g class="fa-group">
            <path class="fa-secondary" fill="currentColor" d="M0 192v272a48 48 0 0 0 48 48h352a48 48 0 0 0 48-48V192zm324.13 141.91a11.92 11.92 0 0 1-3.53 6.89L281 379.4l9.4 54.6a12 12 0 0 1-17.4 12.6l-49-25.8-48.9 25.8a12 12 0 0 1-17.4-12.6l9.4-54.6-39.6-38.6a12 12 0 0 1 6.6-20.5l54.7-8 24.5-49.6a12 12 0 0 1 21.5 0l24.5 49.6 54.7 8a12 12 0 0 1 10.13 13.61zM304 128h32a16 16 0 0 0 16-16V16a16 16 0 0 0-16-16h-32a16 16 0 0 0-16 16v96a16 16 0 0 0 16 16zm-192 0h32a16 16 0 0 0 16-16V16a16 16 0 0 0-16-16h-32a16 16 0 0 0-16 16v96a16 16 0 0 0 16 16z" opacity="0.4"></path>
            <path class="fa-primary" fill="currentColor" d="M314 320.3l-54.7-8-24.5-49.6a12 12 0 0 0-21.5 0l-24.5 49.6-54.7 8a12 12 0 0 0-6.6 20.5l39.6 38.6-9.4 54.6a12 12 0 0 0 17.4 12.6l48.9-25.8 49 25.8a12 12 0 0 0 17.4-12.6l-9.4-54.6 39.6-38.6a12 12 0 0 0-6.6-20.5zM400 64h-48v48a16 16 0 0 1-16 16h-32a16 16 0 0 1-16-16V64H160v48a16 16 0 0 1-16 16h-32a16 16 0 0 1-16-16V64H48a48 48 0 0 0-48 48v80h448v-80a48 48 0 0 0-48-48z"></path>
          </g>
        </svg>
      </div>

      <div id="show-settings">
        <svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
          <g>
            <path class="fa-secondary" fill="currentColor" d="M638.41 387a12.34 12.34 0 0 0-12.2-10.3h-16.5a86.33 86.33 0 0 0-15.9-27.4L602 335a12.42 12.42 0 0 0-2.8-15.7 110.5 110.5 0 0 0-32.1-18.6 12.36 12.36 0 0 0-15.1 5.4l-8.2 14.3a88.86 88.86 0 0 0-31.7 0l-8.2-14.3a12.36 12.36 0 0 0-15.1-5.4 111.83 111.83 0 0 0-32.1 18.6 12.3 12.3 0 0 0-2.8 15.7l8.2 14.3a86.33 86.33 0 0 0-15.9 27.4h-16.5a12.43 12.43 0 0 0-12.2 10.4 112.66 112.66 0 0 0 0 37.1 12.34 12.34 0 0 0 12.2 10.3h16.5a86.33 86.33 0 0 0 15.9 27.4l-8.2 14.3a12.42 12.42 0 0 0 2.8 15.7 110.5 110.5 0 0 0 32.1 18.6 12.36 12.36 0 0 0 15.1-5.4l8.2-14.3a88.86 88.86 0 0 0 31.7 0l8.2 14.3a12.36 12.36 0 0 0 15.1 5.4 111.83 111.83 0 0 0 32.1-18.6 12.3 12.3 0 0 0 2.8-15.7l-8.2-14.3a86.33 86.33 0 0 0 15.9-27.4h16.5a12.43 12.43 0 0 0 12.2-10.4 112.66 112.66 0 0 0 .01-37.1zm-136.8 44.9c-29.6-38.5 14.3-82.4 52.8-52.8 29.59 38.49-14.3 82.39-52.8 52.79zm136.8-343.8a12.34 12.34 0 0 0-12.2-10.3h-16.5a86.33 86.33 0 0 0-15.9-27.4l8.2-14.3a12.42 12.42 0 0 0-2.8-15.7 110.5 110.5 0 0 0-32.1-18.6A12.36 12.36 0 0 0 552 7.19l-8.2 14.3a88.86 88.86 0 0 0-31.7 0l-8.2-14.3a12.36 12.36 0 0 0-15.1-5.4 111.83 111.83 0 0 0-32.1 18.6 12.3 12.3 0 0 0-2.8 15.7l8.2 14.3a86.33 86.33 0 0 0-15.9 27.4h-16.5a12.43 12.43 0 0 0-12.2 10.4 112.66 112.66 0 0 0 0 37.1 12.34 12.34 0 0 0 12.2 10.3h16.5a86.33 86.33 0 0 0 15.9 27.4l-8.2 14.3a12.42 12.42 0 0 0 2.8 15.7 110.5 110.5 0 0 0 32.1 18.6 12.36 12.36 0 0 0 15.1-5.4l8.2-14.3a88.86 88.86 0 0 0 31.7 0l8.2 14.3a12.36 12.36 0 0 0 15.1 5.4 111.83 111.83 0 0 0 32.1-18.6 12.3 12.3 0 0 0 2.8-15.7l-8.2-14.3a86.33 86.33 0 0 0 15.9-27.4h16.5a12.43 12.43 0 0 0 12.2-10.4 112.66 112.66 0 0 0 .01-37.1zm-136.8 45c-29.6-38.5 14.3-82.5 52.8-52.8 29.59 38.49-14.3 82.39-52.8 52.79z" opacity="0.4"></path>
            <path class="fa-primary" fill="currentColor" d="M420 303.79L386.31 287a173.78 173.78 0 0 0 0-63.5l33.7-16.8c10.1-5.9 14-18.2 10-29.1-8.9-24.2-25.9-46.4-42.1-65.8a23.93 23.93 0 0 0-30.3-5.3l-29.1 16.8a173.66 173.66 0 0 0-54.9-31.7V58a24 24 0 0 0-20-23.6 228.06 228.06 0 0 0-76 .1A23.82 23.82 0 0 0 158 58v33.7a171.78 171.78 0 0 0-54.9 31.7L74 106.59a23.91 23.91 0 0 0-30.3 5.3c-16.2 19.4-33.3 41.6-42.2 65.8a23.84 23.84 0 0 0 10.5 29l33.3 16.9a173.24 173.24 0 0 0 0 63.4L12 303.79a24.13 24.13 0 0 0-10.5 29.1c8.9 24.1 26 46.3 42.2 65.7a23.93 23.93 0 0 0 30.3 5.3l29.1-16.7a173.66 173.66 0 0 0 54.9 31.7v33.6a24 24 0 0 0 20 23.6 224.88 224.88 0 0 0 75.9 0 23.93 23.93 0 0 0 19.7-23.6v-33.6a171.78 171.78 0 0 0 54.9-31.7l29.1 16.8a23.91 23.91 0 0 0 30.3-5.3c16.2-19.4 33.7-41.6 42.6-65.8a24 24 0 0 0-10.5-29.1zm-151.3 4.3c-77 59.2-164.9-28.7-105.7-105.7 77-59.2 164.91 28.7 105.71 105.7z"></path>
          </g>
        </svg>
      </div>
    </div>
    <div class='layoutControlsHidden' id="layout-controls-container">
      Settings :)
    </div>
  </div>
</div>
`;



var jstpl_overview = `
<table id='players-overview'>
  <thead>
    <tr id="overview-headers">
      <th></th>
    </tr>
  </thead>
  <tbody id="player-overview-body">
    <tr id="overview-row-1">
      <td class="row-header"><div class="row-rank"><div class="rank-symbol"></div></div></td>
    </tr>
    <tr id="overview-row-2">
      <td class="row-header"><div class="row-rank"><div class="rank-symbol"></div></div></td>
    </tr>
    <tr id="overview-row-3">
      <td class="row-header"><div class="row-rank"><div class="rank-symbol"></div></div></td>
    </tr>
    <tr id="overview-row-4">
      <td class="row-header"><div class="row-rank"><div class="rank-symbol"></div></div></td>
    </tr>
    <tr id="overview-row-5">
      <td class="row-header"><div class="row-rank"><div class="rank-symbol"></div></div></td>
    </tr>
    <tr id="overview-row-0">
      <td class="row-header"><div class="row-rank"><div class="rank-symbol"></div></div></td>
    </tr>
    <tr id="overview-row-6">
      <td class="row-header"><div class="row-rank"><div class="rank-symbol"></div></div></td>
    </tr>
  </tbody>
</table>
`;


var jstpl_heroesModal = `
<div id="hall-container">
  <div id="hall"></div>
</div>
`;

var jstpl_distinctionsModal = `
<div id="evaluation-container">
  <div id="evaluation"></div>
</div>
`;
</script>

{OVERALL_GAME_FOOTER}
