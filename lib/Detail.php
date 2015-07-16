<?php

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
    public function __construct($arResultModifierDetail,$codeProp,$iblockID){
        $this->arResultModifier = $arResultModifierDetail;
        $this->arSectionID = $arResultModifierDetail["PROPERTIES"][$codeProp]["VALUE"];
        $this->iblockID = $iblockID;
    }

    /**
     * @return mixed
     */
    public function getCanonicalSection(){
        $arFilter = array('IBLOCK_ID' => $this->iblockID,"ID"=>$this->arSectionID);
        $rsSect= \CIBlockSection::GetList(array('ID' => 'asc'),$arFilter);

        return $rsSect;
    }

    /**
     * @return bool
     */
    public function emptySection(){
        if(empty($this->arSectionID)){
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function updateURL(){
        global $APPLICATION;
        if(!$this->emptySection()){
            $reqSect = $this->getCanonicalSection();
            if($arSect = $reqSect->GetNext()){
                $itemsCode = $this->arResultModifier["CODE"];
                $canonicalUrl = 'http://'.$_SERVER['SERVER_NAME'].$arSect['SECTION_PAGE_URL'].$itemsCode.'/';
                if($arSect['SECTION_PAGE_URL'].$itemsCode.'/' != $this->arResultModifier['DETAIL_PAGE_URL']){
                    $APPLICATION->AddHeadString('<link rel="canonical" href="'.$canonicalUrl.'"/>');
                }
            }
            return  true;
        }
        return  false;
    }
} 