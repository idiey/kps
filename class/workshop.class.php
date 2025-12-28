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
 * \file        class/workshop.class.php
 * \ingroup     workshop
 * \brief       Workshop entity class
 */

require_once DOL_DOCUMENT_ROOT.'/core/class/commonobject.class.php';

/**
 * Workshop class
 */
class Workshop extends CommonObject
{
	/**
	 * @var string ID to identify managed object
	 */
	public $element = 'workshop';

	/**
	 * @var string Name of table without prefix where object is stored
	 */
	public $table_element = 'workshop';

	/**
	 * @var int  Does this object support multicompany module ?
	 * 0=No test on entity, 1=Test with field entity, 'field@table'=Test with link by field@table
	 */
	public $ismultientitymanaged = 1;

	/**
	 * @var int  Does object support extrafields ? 0=No, 1=Yes
	 */
	public $isextrafieldmanaged = 1;

	/**
	 * @var string String with name of icon for workshop. Must be the part after the 'object_' into object_workshop.png
	 */
	public $picto = 'workshop@workshop';

	/**
	 * @var int Workshop ID
	 */
	public $id;

	/**
	 * @var string Workshop reference
	 */
	public $ref;

	/**
	 * @var int Entity
	 */
	public $entity;

	/**
	 * @var string Workshop label
	 */
	public $label;

	/**
	 * @var string Description
	 */
	public $description;

	/**
	 * @var int Status
	 */
	public $status;

	/**
	 * @var integer|string Creation date
	 */
	public $date_creation;

	/**
	 * @var integer|string Modification date
	 */
	public $tms;

	/**
	 * @var int ID of user who created
	 */
	public $fk_user_author;

	/**
	 * @var int ID of user who modified
	 */
	public $fk_user_modif;

	/**
	 * @var string Public note
	 */
	public $note_public;

	/**
	 * @var string Private note
	 */
	public $note_private;

	/**
	 * @var string Import key
	 */
	public $import_key;

	/**
	 * Constructor
	 *
	 * @param DoliDB $db Database handler
	 */
	public function __construct(DoliDB $db)
	{
		$this->db = $db;
	}

	/**
	 * Create object into database
	 *
	 * @param  User $user User that creates
	 * @param  bool $notrigger false=launch triggers after, true=disable triggers
	 * @return int <0 if KO, Id of created object if OK
	 */
	public function create(User $user, $notrigger = false)
	{
		global $conf;

		$error = 0;

		// Clean parameters
		$this->ref = trim($this->ref);
		$this->label = trim($this->label);
		$this->description = trim($this->description);

		// Check parameters
		if (empty($this->ref)) {
			$this->error = 'ErrorFieldRequired: ref';
			return -1;
		}

		$this->db->begin();

		$now = dol_now();

		$sql = "INSERT INTO ".MAIN_DB_PREFIX."workshop (";
		$sql .= "ref,";
		$sql .= "entity,";
		$sql .= "label,";
		$sql .= "description,";
		$sql .= "status,";
		$sql .= "date_creation,";
		$sql .= "fk_user_author";
		$sql .= ") VALUES (";
		$sql .= "'".$this->db->escape($this->ref)."',";
		$sql .= " ".(int) $conf->entity.",";
		$sql .= " '".$this->db->escape($this->label)."',";
		$sql .= " '".$this->db->escape($this->description)."',";
		$sql .= " ".(int) $this->status.",";
		$sql .= " '".$this->db->idate($now)."',";
		$sql .= " ".(int) $user->id;
		$sql .= ")";

		$resql = $this->db->query($sql);
		if (!$resql) {
			$error++;
			$this->errors[] = "Error ".$this->db->lasterror();
		}

		if (!$error) {
			$this->id = $this->db->last_insert_id(MAIN_DB_PREFIX."workshop");

			if (!$notrigger) {
				// Call trigger
				$result = $this->call_trigger('WORKSHOP_CREATE', $user);
				if ($result < 0) {
					$error++;
				}
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(get_class($this)."::create ".$errmsg, LOG_ERR);
				$this->error .= ($this->error ? ', '.$errmsg : $errmsg);
			}
			$this->db->rollback();
			return -1 * $error;
		} else {
			$this->db->commit();
			return $this->id;
		}
	}

	/**
	 * Load object in memory from the database
	 *
	 * @param int    $id  Id object
	 * @param string $ref Ref
	 * @return int <0 if KO, 0 if not found, >0 if OK
	 */
	public function fetch($id, $ref = null)
	{
		$sql = "SELECT";
		$sql .= " t.rowid,";
		$sql .= " t.ref,";
		$sql .= " t.entity,";
		$sql .= " t.label,";
		$sql .= " t.description,";
		$sql .= " t.status,";
		$sql .= " t.date_creation,";
		$sql .= " t.tms,";
		$sql .= " t.fk_user_author,";
		$sql .= " t.fk_user_modif,";
		$sql .= " t.note_public,";
		$sql .= " t.note_private,";
		$sql .= " t.import_key";
		$sql .= " FROM ".MAIN_DB_PREFIX."workshop as t";
		if ($ref) {
			$sql .= " WHERE t.ref = '".$this->db->escape($ref)."'";
		} else {
			$sql .= " WHERE t.rowid = ".(int) $id;
		}

		$resql = $this->db->query($sql);
		if ($resql) {
			if ($this->db->num_rows($resql)) {
				$obj = $this->db->fetch_object($resql);

				$this->id = $obj->rowid;
				$this->ref = $obj->ref;
				$this->entity = $obj->entity;
				$this->label = $obj->label;
				$this->description = $obj->description;
				$this->status = $obj->status;
				$this->date_creation = $this->db->jdate($obj->date_creation);
				$this->tms = $this->db->jdate($obj->tms);
				$this->fk_user_author = $obj->fk_user_author;
				$this->fk_user_modif = $obj->fk_user_modif;
				$this->note_public = $obj->note_public;
				$this->note_private = $obj->note_private;
				$this->import_key = $obj->import_key;
			}
			$this->db->free($resql);

			return 1;
		} else {
			$this->error = "Error ".$this->db->lasterror();
			return -1;
		}
	}

	/**
	 * Update object into database
	 *
	 * @param  User $user User that modifies
	 * @param  bool $notrigger false=launch triggers after, true=disable triggers
	 * @return int <0 if KO, >0 if OK
	 */
	public function update(User $user, $notrigger = false)
	{
		$error = 0;

		// Clean parameters
		$this->ref = trim($this->ref);
		$this->label = trim($this->label);
		$this->description = trim($this->description);

		// Check parameters
		if (empty($this->id)) {
			return -1;
		}

		$this->db->begin();

		$sql = "UPDATE ".MAIN_DB_PREFIX."workshop SET";
		$sql .= " ref='".$this->db->escape($this->ref)."',";
		$sql .= " label='".$this->db->escape($this->label)."',";
		$sql .= " description='".$this->db->escape($this->description)."',";
		$sql .= " status=".(int) $this->status.",";
		$sql .= " fk_user_modif=".(int) $user->id;
		$sql .= " WHERE rowid=".(int) $this->id;

		$resql = $this->db->query($sql);
		if (!$resql) {
			$error++;
			$this->errors[] = "Error ".$this->db->lasterror();
		}

		if (!$error && !$notrigger) {
			// Call trigger
			$result = $this->call_trigger('WORKSHOP_MODIFY', $user);
			if ($result < 0) {
				$error++;
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(get_class($this)."::update ".$errmsg, LOG_ERR);
				$this->error .= ($this->error ? ', '.$errmsg : $errmsg);
			}
			$this->db->rollback();
			return -1 * $error;
		} else {
			$this->db->commit();
			return 1;
		}
	}

	/**
	 * Delete object in database
	 *
	 * @param User $user User that deletes
	 * @param bool $notrigger false=launch triggers after, true=disable triggers
	 * @return int <0 if KO, >0 if OK
	 */
	public function delete(User $user, $notrigger = false)
	{
		$error = 0;

		$this->db->begin();

		if (!$error && !$notrigger) {
			// Call trigger
			$result = $this->call_trigger('WORKSHOP_DELETE', $user);
			if ($result < 0) {
				$error++;
			}
		}

		if (!$error) {
			$sql = "DELETE FROM ".MAIN_DB_PREFIX."workshop";
			$sql .= " WHERE rowid=".(int) $this->id;

			$resql = $this->db->query($sql);
			if (!$resql) {
				$error++;
				$this->errors[] = "Error ".$this->db->lasterror();
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(get_class($this)."::delete ".$errmsg, LOG_ERR);
				$this->error .= ($this->error ? ', '.$errmsg : $errmsg);
			}
			$this->db->rollback();
			return -1 * $error;
		} else {
			$this->db->commit();
			return 1;
		}
	}

	/**
	 * Return a link to the object card (with optionally the picto)
	 *
	 * @param	int		$withpicto		Include picto in link (0=No picto, 1=Include picto into link, 2=Only picto)
	 * @param	string	$option			On what the link point to ('nolink', ...)
	 * @param	int  	$notooltip		1=Disable tooltip
	 * @param  string  $morecss			Add more css on link
	 * @param  int     $save_lastsearch_value	-1=Auto, 0=No save of lastsearch_values when clicking, 1=Save lastsearch_values whenclicking
	 * @return	string					String with URL
	 */
	public function getNomUrl($withpicto = 0, $option = '', $notooltip = 0, $morecss = '', $save_lastsearch_value = -1)
	{
		global $conf, $langs, $hookmanager;

		if (!empty($conf->dol_no_mouse_hover)) {
			$notooltip = 1; // Force disable tooltips
		}

		$result = '';

		$label = '<u>'.$langs->trans("Workshop").'</u>';
		$label .= '<br>';
		$label .= '<b>'.$langs->trans('Ref').':</b> '.$this->ref;

		$url = dol_buildpath('/workshop/workshop_card.php', 1).'?id='.$this->id;

		if ($option != 'nolink') {
			// Add param to save lastsearch_values or not
			$add_save_lastsearch_values = ($save_lastsearch_value == 1 ? 1 : 0);
			if ($save_lastsearch_value == -1 && preg_match('/list\.php/', $_SERVER["PHP_SELF"])) {
				$add_save_lastsearch_values = 1;
			}
			if ($add_save_lastsearch_values) {
				$url .= '&save_lastsearch_values=1';
			}
		}

		$linkclose = '';
		if (empty($notooltip)) {
			if (!empty($conf->global->MAIN_OPTIMIZEFORTEXTBROWSER)) {
				$label = $langs->trans("ShowWorkshop");
				$linkclose .= ' alt="'.dol_escape_htmltag($label, 1).'"';
			}
			$linkclose .= ' title="'.dol_escape_htmltag($label, 1).'"';
			$linkclose .= ' class="classfortooltip'.($morecss ? ' '.$morecss : '').'"';
		} else {
			$linkclose = ($morecss ? ' class="'.$morecss.'"' : '');
		}

		$linkstart = '<a href="'.$url.'"';
		$linkstart .= $linkclose.'>';
		$linkend = '</a>';

		$result .= $linkstart;
		if ($withpicto) {
			$result .= img_object(($notooltip ? '' : $label), ($this->picto ? $this->picto : 'generic'), ($notooltip ? (($withpicto != 2) ? 'class="paddingright"' : '') : 'class="'.(($withpicto != 2) ? 'paddingright ' : '').'classfortooltip"'), 0, 0, $notooltip ? 0 : 1);
		}
		if ($withpicto != 2) {
			$result .= $this->ref;
		}
		$result .= $linkend;

		return $result;
	}

	/**
	 * Get label of status
	 *
	 * @param  int		$mode          0=long label, 1=short label, 2=Picto + short label, 3=Picto, 4=Picto + long label, 5=Short label + Picto, 6=Long label + Picto
	 * @return string 	               Label of status
	 */
	public function getLibStatut($mode = 0)
	{
		return $this->LibStatut($this->status, $mode);
	}

	// phpcs:disable PEAR.NamingConventions.ValidFunctionName.ScopeNotCamelCaps
	/**
	 * Return the status label
	 *
	 * @param  int		$status        Id status
	 * @param  int		$mode          0=long label, 1=short label, 2=Picto + short label, 3=Picto, 4=Picto + long label, 5=Short label + Picto, 6=Long label + Picto
	 * @return string 	               Label of status
	 */
	public function LibStatut($status, $mode = 0)
	{
		// phpcs:enable
		if (empty($this->labelStatus) || empty($this->labelStatusShort)) {
			global $langs;
			$this->labelStatus[self::STATUS_DRAFT] = $langs->trans('Draft');
			$this->labelStatus[self::STATUS_VALIDATED] = $langs->trans('Validated');
			$this->labelStatusShort[self::STATUS_DRAFT] = $langs->trans('Draft');
			$this->labelStatusShort[self::STATUS_VALIDATED] = $langs->trans('Validated');
		}

		$statusType = 'status'.$status;
		if ($status == self::STATUS_VALIDATED) {
			$statusType = 'status4';
		}

		return dolGetStatus($this->labelStatus[$status], $this->labelStatusShort[$status], '', $statusType, $mode);
	}

	const STATUS_DRAFT = 0;
	const STATUS_VALIDATED = 1;
}
