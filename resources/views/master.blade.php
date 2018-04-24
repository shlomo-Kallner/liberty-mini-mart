<?php
// 
// 

/** Setting the version and MasterView fileName fields
 *  manually was for testing purposes only..
 *  As well as to test the conversion of the static 
 *  templates to Laravel 5.5.0 and to test the use of 
 * PHP variable instead of string litteral with the 
 * @extend blade function.
 * 
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
 * 
 * Now (23/04/2018 18:43), this file's 
 * "switchboard concept" is no longer needed.
 * I'm still using this file to redirect (temporarily) 
 * to the real master page, however, but am doing so manually 
 * with a string litteral.
 * */
?>

{{-- @extends($fileName) --}}

@extends('master_test2')

