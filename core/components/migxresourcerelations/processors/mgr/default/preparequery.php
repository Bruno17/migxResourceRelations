<?php

$config = $modx->migx->customconfigs;
//if (!$modx->hasPermission('quip.thread_list')) return $modx->error->failure($modx->lexicon('access_denied'));
$resource_id = $modx->getOption('resource_id', $scriptProperties, false);

$resources = $modx->getOption('resources', $config, '');
if (empty($scriptProperties['type']) || $scriptProperties['type'] == 'all') {
    $parents = $modx->getOption('parents', $config, '');
}

$selectfields = $config['selectfields'];

$checkdeleted = isset($config['gridactionbuttons']['toggletrash']['active']) && !empty($config['gridactionbuttons']['toggletrash']['active']) ? true : false;

if (!empty($config['joins'])) {
    $chunk = $modx->newObject('modChunk');
    $chunk->setCacheable(false);
    $chunk->setContent($config['joins']);
    $joins = $chunk->process($scriptProperties);
    $joinaliases = $modx->fromJson($joins);
}


$prefix = !empty($config['prefix']) ? $config['prefix'] : null;

$packageName = $config['packageName'];
$packagepath = $modx->getOption('core_path') . 'components/' . $packageName . '/';
$modelpath = $packagepath . 'model/';
$modx->addPackage($packageName, $modelpath, $prefix);

$classname = $config['classname'];

$joinalias = isset($config['join_alias']) ? $config['join_alias'] : '';


if (!empty($joinalias)) {
    if ($fkMeta = $modx->getFKDefinition($classname, $joinalias)) {
        $joinclass = $fkMeta['class'];
    } else {
        $joinalias = '';
    }
}

if ($this->modx->lexicon) {
    $this->modx->lexicon->load($packageName . ':default');
}

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$isCombo = !empty($scriptProperties['combo']);
$start = $modx->getOption('start', $scriptProperties, 0);
$limit = $modx->getOption('limit', $scriptProperties, 20);
$sort = !empty($config['getlistsort']) ? $config['getlistsort'] : 'id';
$sort = $modx->getOption('sort', $scriptProperties, $sort);
$dir = $modx->getOption('dir', $scriptProperties, 'ASC');
$showtrash = $modx->getOption('showtrash', $scriptProperties, '');
$sortConfig = $modx->getOption('sortConfig', $config, '');

if (!empty($sortConfig)) {
    $sort = '';
    $sortConfig = $modx->fromJson($sortConfig);
    
    
}

$c = $modx->newQuery($classname);
$selectfields = !empty($selectfields) ? explode(',', $selectfields) : null;
$c->select($modx->getSelectColumns($classname, $classname, '', $selectfields));

if (!empty($joinalias)) {
    /*
    if ($joinFkMeta = $modx->getFKDefinition($joinclass, 'Resource')){
    $localkey = $joinFkMeta['local'];
    }    
    */
    $c->leftjoin($joinclass, $joinalias);
    $c->select($modx->getSelectColumns($joinclass, $joinalias, 'Joined_'));
}

foreach ($joinaliases as $join) {
    $joinalias = $join['alias'];
    if (!empty($joinalias)) {
        if (!empty($join['classname'])) {
            $joinclass = $join['classname'];
        } elseif ($fkMeta = $modx->getFKDefinition($classname, $joinalias)) {
            $joinclass = $fkMeta['class'];
        } else {
            $joinalias = '';
        }
        if (!empty($joinalias)) {
            /*
            if ($joinFkMeta = $modx->getFKDefinition($joinclass, 'Resource')){
            $localkey = $joinFkMeta['local'];
            }    
            */
            $selectfields = !empty($join['selectfields']) ? explode(',', $join['selectfields']) : null;
            $on = !empty($join['on']) ? $join['on'] : null;
            $c->leftjoin($joinclass, $joinalias, $on);
            $c->select($modx->getSelectColumns($joinclass, $joinalias, $joinalias . '_', $selectfields));
        }
    }
}

/*
$c->leftjoin('poProduktFormat','ProduktFormat', 'format_id = poFormat.id AND product_id ='.$scriptProperties['object_id']);
//$c->select($classname.'.*');

$c->select('ProduktFormat.format_id,ProduktFormat.calctype,ProduktFormat.price,ProduktFormat.published AS pof_published');
*/

//print_r($config['gridfilters']);
if (!empty($resources)) {
    $c->where(array('id:IN' => explode(',', $resources)));
    $parents = '';
}


if (!empty($parents)) {
    $c->where(array('parent:IN' => explode(',', $parents)));
}


if (!empty($config['getlistwhere'])) {

    $chunk = $modx->newObject('modChunk');
    $chunk->setCacheable(false);
    $chunk->setContent($config['getlistwhere']);
    $where = $chunk->process($scriptProperties);
    $c->where($modx->fromJson($where));

}

if (count($config['gridfilters']) > 0) {
    foreach ($config['gridfilters'] as $filter) {

        if (!empty($filter['getlistwhere'])) {

            $requestvalue = $modx->getOption($filter['name'], $scriptProperties, 'all');
            $requestvalue = empty($requestvalue) ? 'all' : $requestvalue;

            if (isset($scriptProperties[$filter['name']]) && $requestvalue != 'all') {

                $chunk = $modx->newObject('modChunk');
                $chunk->setCacheable(false);
                $chunk->setContent($filter['getlistwhere']);
                $where = $chunk->process($scriptProperties);
                $where = strpos($where, '{') === 0 ? $modx->fromJson($where) : $where;

                $c->where($where);
            }
        }
    }
}


if ($modx->migx->checkForConnectedResource($resource_id, $config)) {
    if (!empty($joinalias)) {
        $c->where(array($joinalias . '.resource_id' => $resource_id));
    } else {
        $c->where(array($classname . '.resource_id' => $resource_id));
    }
}

if ($checkdeleted) {
    if (!empty($showtrash)) {
        $c->where(array($classname . '.deleted' => '1'));
    } else {
        $c->where(array($classname . '.deleted' => '0'));
    }
}

$count = $modx->getCount($classname, $c);

if (empty($sort)) {
    if (is_array($sortConfig)) {
        foreach ($sortConfig as $sort) {
            $sortby = $sort['sortby'];
            $sortdir = isset($sort['sortdir']) ? $sort['sortdir'] : 'ASC';
            $c->sortby($sortby, $sortdir);
        }
    }


} else {
    $c->sortby($sort, $dir);
}
