<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in(__DIR__)
    ->exclude(__DIR__ . '/vendors')
;

return Symfony\CS\Config\Config::create()
    ->fixers([
        'short_array_syntax',
        '-unalign_equals',
        '-unalign_double_arrow'
    ])
    ->finder($finder)
;
