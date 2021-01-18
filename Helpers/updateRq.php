<?php

function updateRqList($rqList,$updateData){
    $date = date('Y-m-d',time());

    foreach ($rqList as $rqItem){

        if($rqItem['ENTITY_ID'] == $updateData[0]){
            $name               = preg_replace('/(\d{4}[\.\/\-][01]\d[\.\/\-][0-3]\d)/', '', $rqItem['NAME']); // удаляем старую дату из реквизита
            $resultUpdateRq     = CRest::call('crm.requisite.update',[
                                        'id'     => $rqItem['ID'],
                                        'fields' => [
                                            "NAME"              => "$name $date",
                                            "ACTIVE"            => "Y",
                                            'RQ_INN'            => $updateData[2],
                                            'RQ_KPP'            => $updateData[3],
                                        ]
                                    ]);
            if($resultUpdateRq['result'] == 1){
                CRest::call('crm.timeline.comment.add',[
                    'fields' => [
                        "ENTITY_ID"    => $rqItem['ENTITY_ID'],
                        "ENTITY_TYPE"  => "company",
                        "COMMENT"      => "Реквизиты компании обновленны автоматически!"
                    ]
                ]);
            }
            break;
        }
    }
}
