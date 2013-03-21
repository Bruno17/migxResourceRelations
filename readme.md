migxResourceRelations Extra for MODx Revolution
=======================================


**Author:** Bruno Perner b.perner@gmx.de [webcmsolutions.de](http://www.webcmsolutions.de)

Documentation is available at 

migxResourceRelations is an extra for modx-revolution to create relations between resources. 
It has three MIGXdb-TVs. 

1. For selecting target-resources on the source-resource.
This is a grid with a resource-tree-filter and some search-options for easy selecting target-resources. 

2. For publishing the preselected relations on the target-resource. 
This grid is for publishing and sorting the preselected resources. 
Its possible to show fields, for example an image of an image-TV of source-resources in that grid. Included is a snippet which can be used as a wrapper for getResources to display the selected Relations. 

3. For pulling source-resources into the own preselected relations
This is nearly the same grid as the first one, but can be used on the target-page to pull source-relations into the relations-grid.

Installation:

Install the latest MIGX - vesion by packagemanagement 
https://github.com/Bruno17/MIGX/tree/master/packages version 2.4.1 beta3 ++
Install this package by packagemanagement
https://github.com/Bruno17/migxResourceRelations/tree/master/packages

go to components->MIGX
maintab 'PackageManager'

Package Name: migxresourcerelations

subtab 'create Tables'
click -> Create Tables

maintab 'MIGX'
click -> Import from package 
package: migxresourcerelations


Backend - Usage:

assign to your source-resource-templates the resourcerelations_source - TV
assign to your target-resource-templates the resourcerelations_target - TV

If you want to pull also source-resoures from target-resources into the relations-grid
assign to your target-resource-templates the resourcerelations_pullsources - TV

On source-resources:
you can filter resources by the resource-tree-combobox
click activate to push the source-resource to the target-resource as possible relation
you can publish the relation from here or from target-resource.

On target-resources:
you will see all pushed related resources in the grid, its possible to add content from resources to the image-column
see the chunk: rrRenderImageTVColumn
you can publish/unpublish relations here
you can sort the resources by clicking to 'select' in the 'pos' - column
after that 'select' will change to 'before cancel after' in all records
click into one of them in a record where you want to have the new position.
all records will be resorted, This does also work on paginated grids, while drag/dropping would work well with lots of records 


Frontend - Usage

[[!rrGetRelations? &rrsortby=`pos` &tpl=`@INLINE [[+pagetitle]]<br />` ]]