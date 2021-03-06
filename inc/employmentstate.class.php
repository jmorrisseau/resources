<?php
/*
 * @version $Id: employmentstate.class.php 480 2012-11-09 tynet $
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

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginResourcesEmploymentState extends CommonDropdown {
   
   var $can_be_translated  = true;
   
   static function getTypeName($nb=0) {

      return _n('Employment state', 'Employment states', $nb, 'resources');
   }
   
   static function canCreate() {
      return Session::haveRight('dropdown',array(CREATE, UPDATE, DELETE));
   }

   static function canView() {
      return Session::haveRight('plugin_resources_employment', READ);
   }

   function getAdditionalFields() {

      return array(array('name'  => 'short_name',
                        'label' => __('Short name',  'resources'),
                        'type'  => 'text',
                        'list'  => true),
                  array('name'  => 'is_active',
                        'label' => __('Active'),
                        'type'  => 'bool'),
                  array('name'  => 'is_leaving_state',
                        'label' => __("Employment state at leaving's resource", "resources"),
                        'type'  => 'bool'),
                  );
   }

   /**
    * When an employment's transfer is performed
    *
    * @static
    * @param $ID
    * @param $entity
    * @return ID|int|the
    */
   static function transfer($ID, $entity) {
      global $DB;

      if ($ID>0) {
         // Not already transfer
         // Search init item
         $query = "SELECT *
                   FROM `glpi_plugin_resources_employmentstates`
                   WHERE `id` = '$ID'";

         if ($result=$DB->query($query)) {
            if ($DB->numrows($result)) {
               $data = $DB->fetch_assoc($result);
               $data = Toolbox::addslashes_deep($data);
               $input['name'] = $data['name'];
               $input['entities_id']  = $entity;
               $temp = new self();
               $newID    = $temp->getID($input);

               if ($newID<0) {
                  $newID = $temp->import($input);
               }

               return $newID;
            }
         }
      }
      return 0;
   }

   function getSearchOptions() {

      $tab = parent::getSearchOptions();

      $tab[14]['table']         = $this->getTable();
      $tab[14]['field']         = 'short_name';
      $tab[14]['name']          = __('Short name',  'resources');

      $tab[15]['table']         = $this->getTable();
      $tab[15]['field']         = 'is_active';
      $tab[15]['name']          = __('Active');
      $tab[15]['datatype']      = 'bool';

      $tab[16]['table']         = $this->getTable();
      $tab[16]['field']         = 'is_leaving_state';
      $tab[16]['name']          = __("Employment state at leaving's resource", "resources");
      $tab[16]['datatype']      = 'bool';
      $tab[16]['massiveaction'] = false;

      return $tab;
   }

   /**
    * when an employmentstate is added
    *
    * @return nothing|void
    */
   function post_addItem() {
      global $DB;

      if (isset($this->input["is_leaving_state"]) && $this->input["is_leaving_state"]) {
         $query = "UPDATE `".$this->getTable()."`
                   SET `is_leaving_state` = '0'
                   WHERE `id` <> '".$this->fields['id']."'";
         $DB->query($query);
      }
   }


   /**
    * when an employmentstate is updated
    *
    * @param int $history
    * @return nothing|void
    */
   function post_updateItem($history=1) {
      global $DB;

      if (in_array('is_leaving_state',$this->updates)) {

         if ($this->input["is_leaving_state"]) {
            $query = "UPDATE `".$this->getTable()."`
                      SET `is_leaving_state` = '0'
                      WHERE `id` <> '".$this->input['id']."'";
            $DB->query($query);

         } else {
            Session::addMessageAfterRedirect(__('Be careful: there is no default value'), false, ERROR);
         }
      }
   }

   /**
    * is_active = 1 during a creation
    *
    * @return nothing|void
    */
   function post_getEmpty() {

      $this->fields['is_active'] = 1;
   }


   /**
    * Get the default employmentstate for all employment of a leaving resource
    *
    * @return default employmentstate_id
    **/
   static function getDefault() {
      global $DB;

      foreach ($DB->request('glpi_plugin_resources_employmentstates', array('is_leaving_state' => 1)) as $data) {
         return $data['id'];
      }
      return 0;
   }
}

?>