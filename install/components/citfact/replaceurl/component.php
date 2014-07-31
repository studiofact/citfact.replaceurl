<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
use Citfact\Replaceurl;
use Bitrix\Main\Application;
CModule::includeModule('citfact.replaceurl');

$PROP_CODE =  COption::GetOptionString("citfact.replaceurl", "PROPERTY_CODE", "MAIN_SECTION");
$IBLOCK_ID = COption::GetOptionString("citfact.replaceurl", "IBLOCK_ID", "");
$app = Application::getInstance();
$currentUri = $app->getContext()->getServer()->getRequestUri();

if(!empty($arParams["~array_modifier"]["ITEMS"])){
    $replaceSectURL = new Replaceurl\Section($arParams["~array_modifier"]["ITEMS"],$PROP_CODE,$IBLOCK_ID);
    $arParams["~array_modifier"]["ITEMS"] = $replaceSectURL->updateURL();

}else{
    $replaceDetailURL = new Replaceurl\Detail($arParams["~array_modifier"],$PROP_CODE,$IBLOCK_ID);
    $arParams["~array_modifier"] = $replaceDetailURL->updateURL();
    $replaceDetailURL->localRedirect($currentUri);
}

return $arParams["~array_modifier"];
