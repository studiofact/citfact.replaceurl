<?php

namespace Citfact\Replaceurl;


class Section {
    /**
     * @var int
     */
    protected  $iblocID;

    /**
     * @var string
     */
    protected $codeProp;

    /**
     * @var array
     */
    protected $arSectionID = array();

    /**
     * @var array
     */
    protected $arUpdateLink =array();

    /**
     * @var array
     */
    protected $arResultModifier = array();

    /**
     * @param $arResultModifierItems
     * @param $codeProp
     * @param $iblockID
     */
    public function __construct($arResultModifierItems,$codeProp,$iblockID){
        $this->codeProp = $codeProp;
        $this->iblocID = $iblockID;
        $this->arResultModifier = $arResultModifierItems;
        foreach($arResultModifierItems as $pid => $value){
            if(!empty($value['PROPERTIES'][$this->codeProp]['VALUE'])){
                $this->arSectionID[] = $value['PROPERTIES'][$this->codeProp]['VALUE'];
                $this->arUpdateLink[$pid] = $value['PROPERTIES'][$this->codeProp]['VALUE'];
            }
        }
    }

    /**
     * @return mixed
     */
    public function getSectionList(){
        $arFilter = array('IBLOCK_ID' => $this->iblocID,'ID'=>$this->arSectionID);
        $rsSect = \CIBlockSection::GetList(array('ID' => 'asc'),$arFilter);
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
     * @return array
     */
    public function updateURL(){
        if(!$this->emptySection()){
            $reqSect = $this->getSectionList();
            while($arSect = $reqSect->GetNext()){
                foreach($this->arUpdateLink as $key => &$value){
                    if($value == $arSect['ID']){
                        $itemsCode = $this->arResultModifier[$key]['CODE'];
                        $replaceUrl = $arSect['SECTION_PAGE_URL'].$itemsCode.'/';

                        $this->arResultModifier[$key]['DETAIL_PAGE_URL'] = $replaceUrl;
                        $this->arResultModifier[$key]['~DETAIL_PAGE_URL'] = $replaceUrl;
                    }
                }
            }
            return $this->arResultModifier;
        }
        return $this->arResultModifier;
    }
}