<?php
/**
*
* @package phpBB Extension - LMDI Trashbin extension
* @copyright (c) 2016 Pierre Duhem - LMDI
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace lmdi\trashbin\migrations;

class release_1 extends \phpbb\db\migration\migration
{

	public function effectively_installed()
	{
		return isset($this->config['lmdi_trashbin']);
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\alpha2');
	}

	public function update_data()
	{
		return array(
			// ACP modules
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_TRASHBIN_TITLE'
			)),
			array('module.add', array(
				'acp',
				'ACP_TRASHBIN_TITLE',
				array(
					'module_basename'	=> '\lmdi\trashbin\acp\trashbin_module',
					'modes'			=> array('settings'),
				),
			)),

			// Configuration rows
			array('config.add', array('lmdi_trashbin', 0)),

		);
	}

	public function revert_data()
	{

		return array(
			array('custom', array(array(&$this, 'revert_pruning_state'))),
			array('config.remove', array('lmdi_trashbin')),

			array('module.remove', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_TRASHBIN_TITLE'
			)),

		);
	}

	public function revert_pruning_state()
	{
		$fid = $this->config['lmdi_trashbin'];
		$sql = 'update ${this->table_prefix}' . FORUMS_TABLE . '
			SET enable_prune=0 
			WHERE forum_id='.$fid;
		$this->db->sql_query($sql);
	}


}
