<?php
/**
 * Created by PhpStorm.
 * User: Maiyn6
 * Date: 30.07.14
 * Time: 20:35
 */

namespace Citfact\Replaceurl;


class Section {
    /**
     * @var int
     */
    private $iblocID;

    /**
     * @var string
     */
    private $codeProp;

    /**
     * @var array
     */
    private $arSectionID = array();

    /**
     * @var array
     */
    private $arUpdateLink =array();

    /**
     * @var array
     */
    private $arResultModifier = array();

    /**
     * @param $arResultModifierItems
     * @param $codeProp
     * @param $iblockID
     */
    function __constructor($arResultModifierItems,$codeProp,$iblockID){
        $this->codeProp = $codeProp;
        $this->iblocID = $iblockID;
        $this->arResultModifier = $arResultModifierItems;
        foreach($arResultModifierItems as $pid => &$value){
            if(!empty($value['PROPERTIES'][$this->codeProp]['VALUE'])){
                $this->arSectionID[] = $value['PROPERTIES'][$this->$PROP_CODE]['VALUE'];
                $this->arUpdateLink[$pid] = $value['PROPERTIES'][$this->$PROP_CODE]['VALUE'];
            }
        }
    }

    /**
     * @return mixed
     */
    public function getSectionList(){
        $arFilter = array('IBLOCK_ID' => $this->iblocID,'ID'=>$this->arSectionID);
        $rsSect = CIBlockSection::GetList(array('ID' => 'asc'),$arFilter);
        
        return $rsSect;
    }

    /**
     * @return array|bool
     */
    public function updateURL(){
        if(!empty($this->arSectionID)){
            $reqSect = $this->getSectionList();
            while($arSect = $reqSect->GetNext()){
                foreach($this->arUpdateLink as $key => &$value){
                    if($value == $arSect['ID']){
                        $itemsCode = $this->arResultModifier['ITEMS'][$key]['CODE'];
                        $replaceUrl = $arSect['SECTION_PAGE_URL'].$itemsCode.'/';

                        $this->arResultModifier['ITEMS'][$key]['DETAIL_PAGE_URL'] = $replaceUrl;
                        $this->arResultModifier['ITEMS'][$key]['~DETAIL_PAGE_URL'] = $replaceUrl;
                    }
                }
            }
            return $this->arResultModifier;
        }
        return false;
    }
}