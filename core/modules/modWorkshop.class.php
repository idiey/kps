<?php
/* Copyright (C) 2025 Workshop Management Development Team
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * \defgroup   workshop     Workshop Management module
 * \brief      Workshop Management module descriptor
 * \file       core/modules/modWorkshop.class.php
 * \ingroup    workshop
 * \brief      Description and activation file for module Workshop
 */

include_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';

/**
 * Description and activation class for module Workshop
 */
class modWorkshop extends DolibarrModules
{
	/**
	 * Constructor. Define names, constants, directories, boxes, permissions
	 *
	 * @param DoliDB $db Database handler
	 */
	public function __construct($db)
	{
		global $langs, $conf;
		$this->db = $db;

		// Module number - Must be unique
		$this->numero = 500000;

		// Key text used to identify module (for permissions, menus, etc...)
		$this->rights_class = 'workshop';

		// Family can be 'base' (core modules), 'crm', 'financial', 'hr',
		// 'projects', 'products', 'ecm', 'technic' (advanced modules), 'interface' (integration modules), 'other'
		$this->family = "projects";

		// Module position in the family on 2 digits ('01', '10', '20', ...)
		$this->module_position = '50';

		// Gives the possibility for the module to provide his own family info and position
		$this->familyinfo = array('projects' => array('position' => '50', 'label' => $langs->trans("ModuleFamilyProjects")));

		// Module label (no space allowed), used if translation string 'ModuleWorkshopName' not found
		$this->name = preg_replace('/^mod/i', '', get_class($this));

		// Module description, used if translation string 'ModuleWorkshopDesc' not found
		$this->description = "Workshop and repair service management";

		// Used only if file README.md and README-LL.md not found
		$this->descriptionlong = "Manage workshops, repair jobs, time tracking, and parts inventory";

		// Version
		$this->version = '1.0.0';

		// Key used in llx_const table to save module status enabled/disabled
		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);

		// Name of image file used for this module
		$this->picto = 'workshop@workshop';

		// Define some features supported by module
		$this->module_parts = array(
			// Set this to 1 if module has its own trigger directory (/workshop/core/triggers)
			'triggers' => 1,
			// Set this to 1 if module has its own login method file (/workshop/core/login)
			'login' => 0,
			// Set this to 1 if module has its own substitution function file (/workshop/core/substitutions)
			'substitutions' => 0,
			// Set this to 1 if module has its own menus handler directory (/workshop/core/menus)
			'menus' => 0,
			// Set this to 1 if module overwrite template dir (/workshop/core/tpl)
			'tpl' => 0,
			// Set this to 1 if module has its own barcode directory (/workshop/core/modules/barcode)
			'barcode' => 0,
			// Set this to 1 if module has its own models directory (/workshop/core/modules/xxx)
			'models' => 1,
			// Set this to 1 if module has its own printing directory (/workshop/core/modules/printing)
			'printing' => 0,
			// Set this to 1 if module has its own theme directory (/workshop/theme)
			'theme' => 0,
			// Set this to relative path of css file if module has its own css file
			'css' => array('/workshop/css/workshop.css'),
			// Set this to relative path of js file if module has its own javascript file
			'js' => array('/workshop/js/workshop.js'),
			// Set here all hooks context managed by module. To find available hook context, make a "grep -r '>initHooks(' *" on source code.
			'hooks' => array(
				'thirdpartycard',
				'projectcard',
				'productcard',
			),
			// Set here all workflow context managed by module. To find available workflow context, make a "grep -r 'workflow' *" on source code.
			'workflow' => array(),
		);

		// Data directories to create when module is enabled.
		$this->dirs = array("/workshop/temp");

		// Config pages. Put here list of php page names stored into workshop/admin directory, used to setup module.
		$this->config_page_url = array("setup.php@workshop");

		// Dependencies
		$this->hidden = false; // A condition to hide module
		$this->depends = array('modSociete'); // List of module class names as string that must be enabled if this module is enabled. Example: array('always'=>array('modModuleToEnable1','modModuleToEnable2'), 'FR'=>array('modModuleToEnableFR'...))
		$this->requiredby = array(); // List of module class names as string to disable if this one is disabled. Example: array('modModuleToDisable1', ...)
		$this->conflictwith = array(); // List of module class names as string this module is in conflict with. Example: array('modModuleToDisable1', ...)

		// The language file dedicated to your module
		$this->langfiles = array("workshop@workshop");

		// Prerequisites
		$this->phpmin = array(7, 4); // Minimum version of PHP required by module
		$this->need_dolibarr_version = array(12, 0); // Minimum version of Dolibarr required by module

		// Messages at activation
		$this->warnings_activation = array(); // Warning to show when we activate module. array('always'='text') or array('FR'='textfr','MX'='textmx'...)
		$this->warnings_activation_ext = array(); // Warning to show when we activate an external module. array('always'='text') or array('FR'='textfr','MX'='textmx'...)

		// Constants
		$this->const = array();

		// Array to add new pages in new tabs
		$this->tabs = array();

		// Dictionaries
		$this->dictionaries = array(
			'langs'=>'workshop@workshop',
			'tabname'=>array(MAIN_DB_PREFIX."c_workshop_status"),
			'tablib'=>array("WorkshopStatus"),
			'tabsql'=>array('SELECT rowid, code, label, active FROM '.MAIN_DB_PREFIX.'c_workshop_status'),
			'tabsqlsort'=>array("label ASC"),
			'tabfield'=>array("code,label"),
			'tabfieldvalue'=>array("code,label"),
			'tabfieldinsert'=>array("code,label"),
			'tabrowid'=>array("rowid"),
			'tabcond'=>array('$conf->workshop->enabled')
		);

		// Boxes/Widgets
		$this->boxes = array();

		// Cronjobs
		$this->cronjobs = array();

		// Permissions provided by this module
		$this->rights = array();
		$r = 0;

		// Main menu entries to add
		$this->menu = array();
		$r = 0;

		// Permissions: Read
		$this->rights[$r][0] = $this->numero + 1;
		$this->rights[$r][1] = 'Read workshops and jobs';
		$this->rights[$r][4] = 'read';
		$r++;

		// Permissions: Create/modify
		$this->rights[$r][0] = $this->numero + 2;
		$this->rights[$r][1] = 'Create and modify workshops and jobs';
		$this->rights[$r][4] = 'write';
		$r++;

		// Permissions: Delete
		$this->rights[$r][0] = $this->numero + 3;
		$this->rights[$r][1] = 'Delete workshops and jobs';
		$this->rights[$r][4] = 'delete';
		$r++;

		// Permissions: Export
		$this->rights[$r][0] = $this->numero + 4;
		$this->rights[$r][1] = 'Export workshop data';
		$this->rights[$r][4] = 'export';
		$r++;

		// Main menu entries
		$r = 0;

		// Add workshop menu entry
		$this->menu[$r++] = array(
			'fk_menu'=>'', // Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
			'type'=>'top', // This is a Top menu entry
			'titre'=>'Workshop',
			'mainmenu'=>'workshop',
			'leftmenu'=>'',
			'url'=>'/workshop/workshop_list.php',
			'langs'=>'workshop@workshop', // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
			'position'=>1000 + $r,
			'enabled'=>'$conf->workshop->enabled', // Define condition to show or hide menu entry
			'perms'=>'$user->rights->workshop->read', // Use 'perms'=>'$user->rights->workshop->level1->level2' if you want your menu with a permission rules
			'target'=>'',
			'user'=>2, // 0=Menu for internal users, 1=external users, 2=both
		);

		$this->menu[$r++] = array(
			'fk_menu'=>'fk_mainmenu=workshop',
			'type'=>'left',
			'titre'=>'Workshops',
			'mainmenu'=>'workshop',
			'leftmenu'=>'workshop',
			'url'=>'/workshop/workshop_list.php',
			'langs'=>'workshop@workshop',
			'position'=>1000 + $r,
			'enabled'=>'$conf->workshop->enabled',
			'perms'=>'$user->rights->workshop->read',
			'target'=>'',
			'user'=>2,
		);

		$this->menu[$r++] = array(
			'fk_menu'=>'fk_mainmenu=workshop,fk_leftmenu=workshop',
			'type'=>'left',
			'titre'=>'NewWorkshop',
			'mainmenu'=>'workshop',
			'leftmenu'=>'',
			'url'=>'/workshop/workshop_card.php?action=create',
			'langs'=>'workshop@workshop',
			'position'=>1000 + $r,
			'enabled'=>'$conf->workshop->enabled',
			'perms'=>'$user->rights->workshop->write',
			'target'=>'',
			'user'=>2,
		);

		$this->menu[$r++] = array(
			'fk_menu'=>'fk_mainmenu=workshop,fk_leftmenu=workshop',
			'type'=>'left',
			'titre'=>'ListWorkshops',
			'mainmenu'=>'workshop',
			'leftmenu'=>'',
			'url'=>'/workshop/workshop_list.php',
			'langs'=>'workshop@workshop',
			'position'=>1000 + $r,
			'enabled'=>'$conf->workshop->enabled',
			'perms'=>'$user->rights->workshop->read',
			'target'=>'',
			'user'=>2,
		);
	}

	/**
	 * Function called when module is enabled.
	 * The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
	 * It also creates data directories
	 *
	 * @param string $options Options when enabling module ('', 'noboxes')
	 * @return int 1 if OK, 0 if KO
	 */
	public function init($options = '')
	{
		global $conf, $langs;

		$result = $this->_load_tables('/workshop/sql/');
		if ($result < 0) {
			return -1; // Do not activate module if error 'not allowed' returned when loading module SQL queries (the _load_table run sql with run_sql with the error allowed parameter set to 'default')
		}

		// Create extrafields during init
		//include_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
		//$extrafields = new ExtraFields($this->db);
		//$result1=$extrafields->addExtraField('workshop_myattr1', "New Attr 1 label", 'boolean', 1,  3, 'thirdparty',   0, 0, '', '', 1, '', 0, 0, '', '', 'workshop@workshop', '$conf->workshop->enabled');
		//$result2=$extrafields->addExtraField('workshop_myattr2', "New Attr 2 label", 'varchar', 1, 10, 'project',      0, 0, '', '', 1, '', 0, 0, '', '', 'workshop@workshop', '$conf->workshop->enabled');
		//$result3=$extrafields->addExtraField('workshop_myattr3', "New Attr 3 label", 'varchar', 1, 10, 'bank_account', 0, 0, '', '', 1, '', 0, 0, '', '', 'workshop@workshop', '$conf->workshop->enabled');
		//$result4=$extrafields->addExtraField('workshop_myattr4', "New Attr 4 label", 'select',  1,  3, 'thirdparty',   0, 1, '', array('options'=>array('code1'=>'Val1','code2'=>'Val2','code3'=>'Val3')), 1,'', 0, 0, '', '', 'workshop@workshop', '$conf->workshop->enabled');
		//$result5=$extrafields->addExtraField('workshop_myattr5', "New Attr 5 label", 'text',    1, 10, 'user',         0, 0, '', '', 1, '', 0, 0, '', '', 'workshop@workshop', '$conf->workshop->enabled');

		// Permissions
		$this->remove($options);

		$sql = array();

		return $this->_init($sql, $options);
	}

	/**
	 * Function called when module is disabled.
	 * Remove from database constants, boxes and permissions from Dolibarr database.
	 * Data directories are not deleted
	 *
	 * @param string $options Options when disabling module ('', 'noboxes')
	 * @return int 1 if OK, 0 if KO
	 */
	public function remove($options = '')
	{
		$sql = array();
		return $this->_remove($sql, $options);
	}
}
