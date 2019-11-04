<?php

return [
    '/test/simple/route/' => [1],
    '/route/{parameter}/{secondParameter}/{p}' => [2],
    '/route/{parameter}/test' => [3],
    '/route/{parameter}/test/{test123}' => [4],
    '/{parameter}' => [5],
    'get:/test' => [6],
    'post:/test' => [7],
    'get,post:/test' => [8],
    'post,get:/test' => [9],
];