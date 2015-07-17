<?php

/*
 * @version $Id: recap.php 480 2012-11-09 tynet $
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

//show list of employment linked with a resource
if ($_SESSION['glpiactiveprofile']['interface'] == 'central') {
   Html::header(PluginResourcesResource::getTypeName(2), '', "admin", "pluginresourcesresource");
} else {
   Html::helpHeader(PluginResourcesResource::getTypeName(2));
}

$recap = new PluginResourcesRecap();

if ($recap->canView() || Session::haveRight("config", UPDATE)) {

   //if $_GET["employment_professions_id"] exist this show list of resource / employment
   //by employment rank and profession
   if (isset($_GET["employment_professions_id"]) && !empty($_GET["employment_professions_id"])) {
      $_GET["criteria"][0]["field"]      = "4373";
      $_GET["criteria"][0]["searchtype"] = 'equals';
      $_GET["criteria"][0]["value"]      = $_GET["employment_professions_id"];

      //depending on the date
      $_GET["criteria"][1]["link"]       = 'AND';
      $_GET["criteria"][1]["field"]      = "4367";
      $_GET["criteria"][1]["searchtype"] = 'lessthan';
      $_GET["criteria"][1]["value"]      = $_GET["date"];

      $_GET["criteria"][2]["link"]       = 'AND';
      $_GET["criteria"][2]["field"]      = "4368";
      $_GET["criteria"][2]["searchtype"] = 'contains';
      $_GET["criteria"][2]["value"]      = 'NULL';

      $_GET["criteria"][3]["link"]       = 'OR';
      $_GET["criteria"][3]["field"]      = "4368";
      $_GET["criteria"][3]["searchtype"] = 'morethan';
      $_GET["criteria"][3]["value"]      = $_GET["date"];

      if (isset($_GET["employment_ranks_id"]) && $_GET["employment_ranks_id"] != 0) {
         $_GET["criteria"][4]["link"]       = 'AND';
         $_GET["criteria"][4]["field"]      = "4372";
         $_GET["criteria"][4]["searchtype"] = 'equals';
         $_GET["criteria"][4]["value"]      = $_GET["employment_ranks_id"];
      }

//by resource rank and profession
   } else if (isset($_GET["resource_professions_id"]) && !empty($_GET["resource_professions_id"])) {

      $_GET["criteria"][0]["field"]      = "4375";
      $_GET["criteria"][0]["searchtype"] = 'equals';
      $_GET["criteria"][0]["value"]      = $_GET["resource_professions_id"];

      //depending on the date
      $_GET["criteria"][1]["link"]       = 'AND';
      $_GET["criteria"][1]["field"]      = "4367";
      $_GET["criteria"][1]["searchtype"] = 'lessthan';
      $_GET["criteria"][1]["value"]      = $_GET["date"];

      $_GET["criteria"][2]["link"]       = 'AND';
      $_GET["criteria"][2]["field"]      = "4368";
      $_GET["criteria"][2]["searchtype"] = 'contains';
      $_GET["criteria"][2]["value"]      = 'NULL';

      $_GET["criteria"][3]["link"]       = 'OR';
      $_GET["criteria"][3]["field"]      = "4368";
      $_GET["criteria"][3]["searchtype"] = 'morethan';
      $_GET["criteria"][3]["value"]      = $_GET["date"];

      if (isset($_GET["resource_ranks_id"]) && $_GET["resource_ranks_id"] != 0) {
         $_GET["criteria"][4]["link"]       = 'AND';
         $_GET["criteria"][4]["field"]      = "4374";
         $_GET["criteria"][4]["searchtype"] = 'equals';
         $_GET["criteria"][4]["value"]      = $_GET["resource_ranks_id"];
      }
   }

   $params = Search::manageParams("PluginResourcesRecap", $_GET);
   Search::showGenericSearch("PluginResourcesRecap", $params);
   $recap->showList("PluginResourcesRecap", $params);
} else {
   Html::displayRightError();
}

if ($_SESSION['glpiactiveprofile']['interface'] == 'central') {
   Html::footer();
} else {
   Html::helpFooter();
}
?>