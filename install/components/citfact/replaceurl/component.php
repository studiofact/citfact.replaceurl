<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
use Citfact\Replaceurl;
use Bitrix\Main\Application;
use Bitrix\Main\Config;
use Bitrix\Main\Loader;
Loader::includeModule('citfact.replaceurl');

$PROP_CODE = Config\Option::get("citfact.replaceurl", "PROPERTY_CODE", "MAIN_SECTION");
$IBLOCK_ID = Config\Option::get("citfact.replaceurl", "IBLOCK_ID", "");
$replaceDetailURL = new Replaceurl\Detail($arParams["~array_modifier"],$PROP_CODE,$IBLOCK_ID);
$replaceDetailURL->updateURL();


