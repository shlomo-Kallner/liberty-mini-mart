<?php
// setting the version and fileName fields manually for testing purposes only..
$version = 1;
$fileName = 'master_';

if ($version === 0) {
    $fileName .= 'themewagon';
} elseif ($version == 1) {
    $fileName .= 'bootstrapious';
} elseif ($version == 2) {
    $fileName .= 'startbootstrap';
} elseif ($version == 3) {
    $fileName .= 'bootstrapmade';
}
//dd($fileName);
?>

@extends($fileName)

