<?php
function getRqDoesNotExist($rqList,$companyIdList){

    $rqIdList = [];
    foreach ($rqList as $rqItem) {
        if(!array_key_exists($rqItem['ENTITY_ID'],$companyIdList)){
            $rqIdList[] = $rqItem['ENTITY_ID'];
        }
    }
    return array_diff($companyIdList,$rqIdList);
}
