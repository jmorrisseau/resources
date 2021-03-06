<?php

/*
 * @version $Id: resourcetreetypes.php 480 2012-11-09 tsmr $
  -------------------------------------------------------------------------
  Resources plugin for GLPI
  Copyright (C) 2006-2012 by the Resources Development Team.

  https://forge.indepnet.net/projects/resources
  -------------------------------------------------------------------------

  LICENSE

  This file is part of Resources.

  Resources is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  Resources is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with Resources. If not, see <http://www.gnu.org/licenses/>.
  --------------------------------------------------------------------------
 */

$AJAX_INCLUDE = 1;

include ('../../../inc/includes.php');
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if (isset($_GET['node'])) {

   $target = "resource.php";

   $nodes = array();

   // Root node
   if ($_GET['node'] == -1) {
      $entity = $_SESSION['glpiactive_entity'];

      $where = " WHERE `glpi_plugin_resources_resources`.`is_deleted` = '0' ";
      $where.=getEntitiesRestrictRequest("AND", "glpi_plugin_resources_resources");
      $restrict = "`id` IN (
                  SELECT DISTINCT `plugin_resources_contracttypes_id`
                  FROM `glpi_plugin_resources_resources`
                  $where)
                  GROUP BY `name`
                  ORDER BY `name`";
      $contracts = getAllDatasFromTable("glpi_plugin_resources_contracttypes", $restrict);
      
      if (!empty($contracts)) {
         foreach ($contracts as $contract) {
            $path                         = array();
            $ID                           = $contract['id'];

            $path['data']['title']        = Dropdown::getDropdownName("glpi_plugin_resources_contracttypes", $ID);
            $path['attr']['id']           = 'ent'.$ID;
//            if ($entity == 0) {
//               $link = "&link[1]=AND&searchtype[1]=contains&contains[1]=NULL&field[1]=80";
//            } else {
//               $link = "&link[1]=AND&searchtype[1]=contains&contains[1]=".Dropdown::getDropdownName("glpi_entities", $entity)."&field[1]=80";
//            }
            $path['data']['attr']['href'] = $CFG_GLPI["root_doc"]."/plugins/resources/front/$target?criteria[0][field]=3&criteria[0][searchtype]=equals&criteria[0][value]=$ID&search=Rechercher&itemtype=PluginResourcesResource&start=0";

            $nodes[] = $path;
         }
      }
   } 
   
   echo json_encode($nodes);
}


?>