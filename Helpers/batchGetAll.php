<?php
function batchGetAll( $method,$filter = [],$select=["*",'PROPERTY_*'], $order = ["ID"=>"ASC"] ){
    $Res = CRest::callBatch([
        'list_' => [
            'method' => $method,
            'params' => [
                "order"  => $order  ,
                'filter' => $filter ,
                'select' => $select ,
            ]
        ],
    ]);
    $total          =  $Res['result']['result_total']['list_']; // узнаем сколько всего есть товаров
    $iteration      = ceil(($total / 50)); // получаем кол-во запросов которое нужно сделать к Битрикс
    $batchWrap      = []; // Обертка для пакетов запросов
    $batchParam     = []; // Пакет из 50 запросов
    $result         = [];
    $totalResult    = [];
    $start          = 0 ; // указатель с какой страницы забрать данные для последующих запросов
    for ($i = 0; $i < $iteration; $i++ ){
        if(count($batchParam) === 50){  // Если собрали 50 запросов
            $batchWrap[] = $batchParam; // Поместим запросы в обертку
            $batchParam  = []         ; // Сбросим текущий массив
        }
        $batchParam = array_merge($batchParam,[
            "list_$i" => [
                'method' => $method,
                'params' => [
                    "order"  => $order  ,
                    'filter' => $filter ,
                    'select' => $select ,
                    'start'  => $start  ,
                ]
            ]
        ]);
        $start = ($start + 50);
    }
    $batchWrap[] = $batchParam; // Последний массив добавим в обертку
    foreach ($batchWrap as $item){
        $result[] = CRest::callBatch($item); // Каждая итерация вернет 50 ответов по 50 товаров (2500 шт)
    }
    foreach ($result as $item){
        foreach ($item['result']['result'] as $res){
            $totalResult  = array_merge($totalResult,$res); // складываем все ответы в один массив
        }
    }
    return $totalResult;
}

