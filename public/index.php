<?php

session_start();

$size = '40px';
$winRows = $_SESSION['win_rows'];

$wins = [
  [0, 1, 2], 
  [3, 4, 5],
  [6, 7, 8],

  [0, 3, 6],
  [1, 4, 7],
  [2, 5, 8],

  [0, 4, 8],
  [6, 4, 2],
];

function getWinRows() {
  global $wins;
  $map = $_SESSION['map'];

  foreach($wins as $val) {
    if($map[$val[0]] && $map[$val[0]] == $map[$val[1]] && $map[$val[0]] == $map[$val[2]])
      return $val;
  }

  return null;
}

if(!$_SESSION['map'] || $_SERVER['REQUEST_METHOD'] !== 'POST') {
  $winRows = $_SESSION['win_rows'] = null;
  $_SESSION['player'] = 0;

  $_SESSION['map'] = [
    0, 0, 0,
    0, 0, 0,
    0, 0, 0
  ];
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item'])) {
  if(!$_SESSION['map'][$_POST['item']]) {
    $_SESSION['map'][$_POST['item']] = $_SESSION['player'] + 1;
    $_SESSION['player'] = $_SESSION['player'] + 1;
    $_SESSION['player'] = $_SESSION['player'] % 2;
  }
  
  $winRows = $_SESSION['win_rows'] = getWinRows();
}

$winNames = ['крестики', 'нолики'];
$actionNames = ['Победили', 'Ходят'];
$map = $_SESSION['map'];
$player = $_SESSION['player'];
?>

<div style="display: flex; flex-direction: column; gap: 10px;">
  <a href="/">
    <button>Reset</button>
  </a>

  <p>
    <?=$actionNames[$winRows == null]?> <?=$winNames[$player] ?>
  </p>

  <form method="POST" style="display: grid; grid-template-columns: repeat(3, <?=$size?>); gap: 10px;">
    <?php foreach($map as $key => $value) { ?>
      <button 
        <?php if($map[$key] > 0 || $winRows) { ?>disabled <?php } ?> 
        type="submit" 
        name="item" 
        style="height: <?=$size?>; <?php if($map[$key] == 0 && !$winRows) { ?> cursor: pointer; <?php } ?> <?php if($winRows && in_array($key, $winRows)) { ?> color: red; <?php } ?>"
        value="<?=$key?>"
      >
        <?php if($value === 1) { ?>
          X
        <?php } ?>

        <?php if($value === 2) { ?>
          O
        <?php } ?>
      </button>
    <?php } ?>
  </form>
</div>