<?php
/**
*
* @package phpBB Extension - DG Calendar
* @copyright (c) 2015 DG Kim
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
* German translation by franki (http://dieahnen.de/ahnenforum/)
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'AM'					=> 'AM',
	'APRIL'					=> 'April',
	'AUGUST'				=> 'August',
	
	'CALENDAR'				=> 'Kalender',
	'CALENDAR_PAGE'			=> 'Kalender',
	'COMMENT'				=> 'Kommentar',
	'CREATE_EVENT'			=> 'Veranstaltung erstellen',

	'DATE'					=> 'Datum',
	'DECEMBER'				=> 'Dezember',
	'DELETE'				=> 'Löschen',
	'DESCRIPTION'			=> 'Beschreibung',

	'EDIT'					=> 'ändern',
	'EDIT_EVENT'			=> 'Event ändern',
	'END_TIME'				=> 'Endzeit',
	'EVENT_BY'				=> 'Event von %s',
	'EVENT_DELETE'			=> 'Event löschen',
	'EVENT_DELETE_CONFIRM'	=> 'Bist Du sicher, dass Du dieses Event löschen möchtest?',
	'EVENT_DELETE_SUCCESSFUL'	=> 'Event erfolgreich gelöscht!',
	'EVENT_LOCK'			=> 'Event gesperrt',
	'EVENT_NOT_DELETED'		=> 'Event nicht gelöscht.',
	'EVENT_SUCCESSFUL'		=> 'Veranstaltung erfolgreich erstellt!',
	'EVENT_SUCCESSFUL_EDIT'	=> 'Event erfolgreich bearbeitet!',

	'FEBRUARY'				=> 'Februar',
	'FIELD_OPTIONAL'		=> 'Dieses Feld optional',
	'FIELD_REQUIRED'		=> 'Feld %s ist erforderlich.',
	'FRIDAY'				=> 'Freitag',
	'FRONT_PAGE'			=> 'Titelseite',

	'JANUARY'				=> 'Januar',
	'JULY'					=> 'Juli',
	'JUNE'					=> 'Juni',

	'LAST_5_EVENTS'			=> 'Letzte 5 Veranstaltungen Verfasst',

	'MARCH'					=> 'March',
	'MAY'					=> 'May',
	'MCP_CALENDAR'			=> 'Kalender MCP',
	'MODERATE_CALENDAR'		=> 'Kalender-Moderator-Control-Panel',
	'MONDAY'				=> 'Montag',
	'MONTH'					=> 'Monat',

	'NEW_EVENT'				=> 'Neue Kalender-Veranstaltung',
	'NO_EVENTS'				=> 'Es sind keine Veranstaltungen zum anzeigen.',
	'NOVEMBER'				=> 'November',

	'OCTOBER'				=> 'Oktober',

	'PM'					=> 'PM',

	'QUICKMOD_TOOLS'		=> 'Quick-Mod Tools',

	'REPORT'				=> 'Report',
	'RETURN_CALENDAR'		=> 'Zurück zum Kalender',
	'RETURN_EVENT'			=> 'Zurück zum Event',

	'THURSDAY'				=> 'Donnerstag',
	'TITLE'					=> 'Titel',
	'TUESDAY'				=> 'Dienstag',

	'SATURDAY'				=> 'Samstag',
	'SEPTEMBER'				=> 'September',
	'START_TIME'			=> 'Startzeit',
	'SUNDAY'				=> 'Sonntag',

	'VIEW'					=> 'Ansicht',
	'VIEW_EVENT'			=> 'Veranstaltung anzeigen',

	'WEDNESDAY'				=> 'Mittwoch',
	'WEEK'					=> 'Woche',
	'WRONG_TIME'			=> 'Startzeiteit muss vor der Endzeit sein.',

	'YEAR'					=> 'Jahr',

	// permissions
	'ACL_U_EVENT_REPORT'		=> 'Kann Events melden',
	'ACL_U_NEW_EVENT'			=> 'Kann Events erstellen',
	'ACL_U_SELF_DELETE'			=> 'Kann eigenen Events löschen',
	'ACL_U_SELF_EDIT'			=> 'Kann eigenen Events bearbeiten',
	
	'ACL_M_CALENDAR'			=> 'Kann den Kalender moderieren',
));
