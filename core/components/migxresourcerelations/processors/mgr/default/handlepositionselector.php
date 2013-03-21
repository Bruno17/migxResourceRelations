<?php

include 'preparequery.php';

$newpos_id = $modx->getOption('new_pos_id', $scriptProperties, 0);
$col = $modx->getOption('col', $scriptProperties, '');
$object_id = $modx->getOption('object_id', $scriptProperties, 0);

$col = explode(':', $col);
if (!empty($newpos_id) && !empty($object_id) && count($col) > 1) {
    $workingobject = $modx->getObject($classname,$object_id);
    $posfield = $col[0];
    $position = $col[1];

    if ($collection = $modx->getCollection($classname, $c)) {
        $curpos = 1;
        foreach ($collection as $object) {
            $id = $object->get('id');
            if ($id == $newpos_id && $position == 'before'){
                $workingobject->set($posfield, $curpos);
                $workingobject->save();
                $curpos++;    
            }
            if ($id != $object_id) {
                $object->set($posfield, $curpos);
                $object->save();
                $curpos++;
            }
            if ($id == $newpos_id && $position == 'after'){
                $workingobject->set($posfield, $curpos);
                $workingobject->save();
                $curpos++;    
            }
        }
    }
}


$modx->cacheManager->refresh(array(
    'db' => array(),
    'auto_publish' => array('contexts' => $contexts),
    'context_settings' => array('contexts' => $contexts),
    'resource' => array('contexts' => $contexts),
    ));
return $modx->error->success();
