<?php

include 'preparequery.php';

if ($isCombo || $isLimit) {
    $c->limit($limit, $start);
}

$c->prepare();
//echo $c->toSql();
if ($c->prepare() && $c->stmt->execute()) {
    //echo $c->toSql();
    //$debug['sql'] = $c->toSql();
    $collection = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
}
foreach ($collection as $row) {
    $row['ResourceRelation_published'] = !empty($row['ResourceRelation_published']) ? 1 : 0;
    $row['ResourceRelation_active'] = !empty($row['ResourceRelation_active']) ? 1 : 0;    
    
    if ($config['sourceortarget'] == 'source'){
        $row['published'] = $row['ResourceRelation_published'];
        $row['Joined_active'] = $row['ResourceRelation_active'];
    }
    
    $rows[] = $row;
}

$rows = $modx->migx->checkRenderOptions($rows);

//$c->sortby($sort,$dir);
//$c->prepare();echo $c->toSql();
//$collection = $modx->getCollection($classname, $c);
/*
$rows = array();
foreach ($collection as $row) {
$rows[] = $row->toArray();
}
*/
