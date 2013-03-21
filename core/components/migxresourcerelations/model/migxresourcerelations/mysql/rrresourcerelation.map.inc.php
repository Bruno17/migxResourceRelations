<?php
$xpdo_meta_map['rrResourceRelation']= array (
  'package' => 'migxresourcerelations',
  'version' => '1.1',
  'table' => 'rr_resource_relations',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'source_id' => 0,
    'target_id' => 0,
    'active' => 0,
    'published' => 0,
    'pos' => 0,
    'extended' => '',
  ),
  'fieldMeta' => 
  array (
    'source_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'target_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'published' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'pos' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'extended' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'json',
      'null' => false,
      'default' => '',
    ),
  ),
  'aggregates' => 
  array (
    'Source' => 
    array (
      'class' => 'modResource',
      'local' => 'source_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Target' => 
    array (
      'class' => 'modResourse',
      'local' => 'target_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
