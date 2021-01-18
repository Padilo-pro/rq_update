<?php

function dd(...$vars) {
  echo '<pre style="    background: black;
    padding: 15px;
    color: #0f0;
    font-size: 15px;
    font-weight: 900;
    font-family: monospace;
    width: max-content;
    position: relative;">';
  foreach ($vars as $var){

    print_r($var);
  }
  echo '</pre>';
  die();
}
