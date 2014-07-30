<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
use Citfact\Replaceurl;
$PROP_CODE =  COption::GetOptionString("citfact.replaceurl", "PROPERTY_CODE", "MAIN_SECTION");
$IBLOCK_ID = COption::GetOptionString("citfact.replaceurl", "IBLOCK_ID", "");

if(!empty($arParams["~array_modifier"]["ITEMS"])){
    $replaceSectURL = new Replaceurl\Section($arParams["~array_modifier"]["ITEMS"],$PROP_CODE,$IBLOCK_ID);
    $arParams["~array_modifier"]["ITEMS"] = $replaceSectURL->updateURL();
}else{
    $replaceDetailURL = new Replaceurl\Detail($arParams["~array_modifier"],$PROP_CODE,$IBLOCK_ID);
    $arParams["~array_modifier"] = $replaceDetailURL->updateURL();
}

return $arParams["~array_modifier"];


/*if(!empty($arParams["~array_modifier"]["ITEMS"])){
	foreach($arParams["~array_modifier"]["ITEMS"] as $pid => &$value){
		if(!empty($value["PROPERTIES"][$PROP_CODE]["VALUE"])){
			$arSectionID[] = $value["PROPERTIES"][$PROP_CODE]["VALUE"];
			$arUpdateLink[$pid] = $value["PROPERTIES"][$PROP_CODE]["VALUE"];
		}
	}
	if(!empty($arSectionID)){
		$arFilter = array('IBLOCK_ID' => $IBLOCK_ID,"ID"=>$arSectionID);
		$rsSect = CIBlockSection::GetList(array('ID' => 'asc'),$arFilter);

		while($arSect = $rsSect->GetNext()){
			foreach($arUpdateLink as $key => &$value){
				if($value == $arSect["ID"]){
					$itemsCode = $arParams["~array_modifier"]["ITEMS"][$key]["CODE"];
					$replaceUrl = $arSect["SECTION_PAGE_URL"].$itemsCode;
					
					$arParams["~array_modifier"]["ITEMS"][$key]["DETAIL_PAGE_URL"] = $arSect["SECTION_PAGE_URL"].$itemsCode."/";
					$arParams["~array_modifier"]["ITEMS"][$key]["~DETAIL_PAGE_URL"] = $arSect["SECTION_PAGE_URL"].$itemsCode."/";
				}
			}
		}
	}
}else{	
	$arSectionID = $arParams["~array_modifier"]["PROPERTIES"][$PROP_CODE]["VALUE"];

	$arFilter = array('IBLOCK_ID' => $IBLOCK_ID,"ID"=>$arSectionID); 
	if(!empty($arSectionID)){
		$rsSect= CIBlockSection::GetList(array('ID' => 'asc'),$arFilter);
		if($arSect = $rsSect->GetNext()){
			$itemsCode = $arParams["~array_modifier"]["CODE"];
            $replaceUrl = $arSect['SECTION_PAGE_URL'].$itemsCode.'/';
			$arParams["~array_modifier"]["DETAIL_PAGE_URL"] = $arSect["SECTION_PAGE_URL"].$itemsCode."/";
			$arParams["~array_modifier"]["~DETAIL_PAGE_URL"] = $arSect["SECTION_PAGE_URL"].$itemsCode."/";
			
			$currentUrl = $APPLICATION->GetCurPageParam("", array(),false);
			$currentUrl = explode('?',$currentUrl);
			$currentUrlParams = !empty($currentUrl[1])? "?".$currentUrl[1] : "";

			if($currentUrl[0] != $arParams["~array_modifier"]["DETAIL_PAGE_URL"]){
				LocalRedirect($arParams["~array_modifier"]["DETAIL_PAGE_URL"].$currentUrlParams,true,"301 Moved permanently");
			}
		}
	}
}
		
return $arParams["~array_modifier"];*/
