<?php
/**
*
* @package phpBB Extension - DG Calendar
* @copyright (c) 2015 DG Kim
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dg\calendar\migrations;

class dev_0_1_3 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\dg\calendar\migrations\dev_0_1_2');
	}
	
	public function update_data()
	{
		return array(
			array('permission.add', array('u_event_comment')),
			array('permission.add', array('u_calendar')),
			
			array('config.update', array('calendar_dg_version', '0.1.3')),
		);
	}
	
	public function update_schema()
	{
		return array(
			'add_tables' => array(
				$this->table_prefix . 'event_invites' => array(
			  		'COLUMNS' => array(
					'id' => array('INT:11', NULL, 'auto_increment'),
					'timestamp' => array('INT:11', 0),
					'event_id' => array('INT:11', 0),
					'invite_id' => array('INT:11', 0),
					'invite_receive_id' => array('INT:11', 0),
			  	),
			  
				'PRIMARY_KEY' => 'id',
				),
			),
		);
	}
	
	public function revert_schema()
	{
		return array(
			'drop_tables'	=> array(
				$this->table_prefix . 'calendar_config'
			),
		);
	}
}
