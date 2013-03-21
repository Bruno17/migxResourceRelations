migxResourceRelations


Author: Bruno Perner b.perner@gmx.de
Copyright 2013

Official Documentation: 

Bugs and Feature Requests: https://github.com/Bruno17/migxResourceRelations

Questions: http://forums.modx.com

Created by MyComponentMigxUI

Description:

migxResourceRelations is an extra for modx-revolution to create relations between resources. 
It has two MIGXdb-TVs. 

1. For selecting target-resources on the source-resource.
This is a grid with a resource-tree-filter and some seach-options for easy selecting target-resources. 

2. For publishing the preselected relations on the target-resource. 
This grid is for publishing and sorting the preselected resources. 
Its possible to show fields, for example an image of an image-TV of source-resources in that grid. Included is a snippet which can be used as a wrapper for getResources to display the selected Relations. 



Installation:


Backend - Usage


Frontend - Usage

[[!rrGetRelations? &rrsortby=`pos` &tpl=`@INLINE [[+pagetitle]]<br />` ]]