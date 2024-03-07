<?php
/****************************************************************************************************
 * Datei: includes/init.smarty.php                                                                  *
 * Datum: 2024-03-07 21:14                                                                          *
 * Abruf: composer require smarty/smarty                                                            *
 ****************************************************************************************************/

//
// Notwendige Dateien einbinden.
require_once "smarty-4.3.5/vendor/autoload.php";
require_once "classes/smarty.ext.php";

//
// Verbindung herstellen
$smarty = new SmartyExt();
$smarty->force_compile = false;
$smarty->caching = true;
$smarty->debugging = false;
$smarty->cache_dir = "caches";
$smarty->config_dir = "configs";
$smarty->compile_dir = "compiles";
$smarty->template_dir = "templates";
