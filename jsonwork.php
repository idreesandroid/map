<?php

$map = '[{"type":"MARKER","id":null,"geometry":[30.389072117426423,69.3857837463379]}]';

$data = json_decode($map);

print_r($data[0]->geometry[0]);