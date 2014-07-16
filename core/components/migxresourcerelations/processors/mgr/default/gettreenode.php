<?php

//if (!$modx->hasPermission('quip.thread_list')) return $modx->error->failure($modx->lexicon('access_denied'));

$config = $modx->migx->customconfigs;

$textfield = $config['gridfilters'][$scriptProperties['searchname']]['combotextfield'];
$idfield = $config['gridfilters'][$scriptProperties['searchname']]['comboidfield'];
$idfield = empty($idfield) ? $textfield : $idfield;

$default_parent = $config['gridfilters'][$scriptProperties['searchname']]['default'];

$prefix = !empty($config['prefix']) ? $config['prefix'] : null;
$packageName = $config['packageName'];

$packagepath = $modx->getOption('core_path') . 'components/' . $packageName . '/';
$modelpath = $packagepath . 'model/';

$modx->addPackage($packageName, $modelpath, $prefix);
$classname = $config['classname'];

if ($this->modx->lexicon) {
    $this->modx->lexicon->load($packageName . ':default');
}

//$joinalias = 'Parent';

if (!empty($joinalias)) {
    if ($fkMeta = $modx->getFKDefinition($classname, $joinalias)) {
        $joinclass = $fkMeta['class'];
    } else {
        $joinalias = '';
    }
}

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$isCombo = !empty($scriptProperties['combo']);
$start = $modx->getOption('start', $scriptProperties, 0);
$limit = $modx->getOption('limit', $scriptProperties, 20);
$mode = $modx->getOption('searchname', $scriptProperties, 'year');
$context = $modx->getOption('context', $scriptProperties, 'alle');
$node = $modx->getOption('node', $scriptProperties, '');

$c = $modx->newQuery($classname);
//$count = $modx->getCount($classname,$c);

$nodeparts = explode('_', $node);
if (count($nodeparts) > 1) {
    $context = $nodeparts[0];
    $parent = $nodeparts[1];
    
    if ($context == 'currentctx' && !empty($default_parent)){
        $parent = $default_parent;
    }

    //$c->where(array('context_key' => $context));
}
else{
    $parent = $nodeparts[0];
}

$c->where(array('parent' => $parent));
$c->where(array('isfolder' => '1'));


$rows = array();

$sort = $modx->getOption('sort', $scriptProperties, $textfield);
$dir = $modx->getOption('dir', $scriptProperties, 'ASC');
if (!empty($joinalias)) {
    $c->leftjoin($joinclass, $joinalias);
    //$c->select($modx->getSelectColumns($joinclass, $joinalias, $joinalias . '_'));
}
$c->select($classname . '.id, ' . $idfield . ' as id, ' . $textfield . ' as text');

$c->sortby($sort, $dir);
//$stmt = $c->prepare();
//echo $c->toSql();
//$stmt->execute();
$rows = array();
if ($collection = $modx->getCollection($classname, $c)) {
    foreach ($collection as $object) {
        $row = $object->toArray('',false,true);
        $row['leaf'] = $modx->getObject($classname,array('isfolder'=>1,'parent'=>$row['id'])) ? false : true;
        $rows[] = $row;
    }
}

$count = count($rows);


return $modx->toJson($rows);
