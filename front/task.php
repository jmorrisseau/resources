<?php

/*
 * @version $Id: task.php 480 2012-11-09 tsmr $
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

include ('../../../inc/includes.php');

Html::header(PluginResourcesResource::getTypeName(2), '', "admin", "pluginresourcesresource");

$task = new PluginResourcesTask();

if (($task->canView() || Session::haveRight("config", UPDATE))) {
   //if $_GET["plugin_resources_resources_id"] exist this show list of tasks from a resource
   //else show all resources
   if (isset($_GET["plugin_resources_resources_id"]) && !empty($_GET["plugin_resources_resources_id"])) {
      $_GET["field"] = array(0 => "13");
      $_GET["contains"] = array(0 => $_GET["plugin_resources_resources_id"]);
   }

   Search::show("PluginResourcesTask");
   
   
} else {
   Html::displayRightError();
}

Html::footer();
?>