<?php
/**
 * Created by PhpStorm.
 * User: Maiyn6
 * Date: 30.07.14
 * Time: 21:07
 */

namespace Citfact\Replaceurl;


class Detail {
    /**
     * @var int
     */
    private $arSectionID;

    /**
     * @var int
     */
    private $iblockID;

    /**
     * @var array
     */
    private $arResultModifier = array();

    /**
     * @param $arResultModifierDetail
     * @param $iblockID
     * @param $codeProp
     */
    function __constructor($arResultModifierDetail,$codeProp,$iblockID){
        $this->arResultModifier = $arResultModifierDetail;
        $this->arSectionID = $arResultModifierDetail["PROPERTIES"][$codeProp]["VALUE"];
        $this->iblockID = $iblockID;
    }

    /**
     * @return mixed
     */
    public function getSectionDetail(){
        $arFilter = array('IBLOCK_ID' => $this->iblockID,"ID"=>$this->arSectionID);
        $rsSect= CIBlockSection::GetList(array('ID' => 'asc'),$arFilter);

        return $rsSect;
    }

    /**
     * @return array|bool
     */
    public function updateURL(){
        if(!empty($this->arSectionID)){
            $reqSect = $this->getSectionDetail();
            if($arSect = $reqSect->GetNext()){
                $itemsCode = $this->arResultModifier["CODE"];
                $replaceUrl = $arSect['SECTION_PAGE_URL'].$itemsCode.'/';

                $this->arResultModifier["DETAIL_PAGE_URL"] = $replaceUrl;
                $this->arResultModifier["~DETAIL_PAGE_URL"] = $replaceUrl;

                //$this->localRedirect();
            }
            return  $this->arResultModifier;
        }
        return  false;
    }

    public function localRedirect(){
        /*$currentUrl = $APPLICATION->GetCurPageParam("", array(),false);
        $currentUrl = explode('?',$currentUrl);
        $currentUrlParams = !empty($currentUrl[1])? "?".$currentUrl[1] : "";

        if($currentUrl[0] !=  $this->arResultModifier["DETAIL_PAGE_URL"]){
            LocalRedirect( $this->arResultModifier["DETAIL_PAGE_URL"].$currentUrlParams,true,"301 Moved permanently");
        }*/
    }
} 