<?php
// setting the version and fileName fields manually for testing purposes only..
$version = 5;
$fileName = 'master_';

if ($version === 0) {
    $fileName .= 'themewagon';
} elseif ($version == 1) {
    $fileName .= 'bootstrapious';
} elseif ($version == 2) {
    $fileName .= 'startbootstrap';
} elseif ($version == 3) {
    $fileName .= 'bootstrapmade_flexor';
} elseif ($version == 4) {
    $fileName .= 'bootstrapmade_sailor';
} elseif ($version == 5) {
    $fileName .= 'test';
}
//dd($fileName);
?>

@extends($fileName)

