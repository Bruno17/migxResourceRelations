$modx->resource = $modx->getObject('modResource',$docid);
$image = $modx->resource->getTvValue($tvname);
$modx->setPlaceholder('image',$modx->getOption('base_url').$image);