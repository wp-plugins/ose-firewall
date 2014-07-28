<?php
/**
 * @version     2.0 +
 * @package       Open Source Excellence Security Suite
 * @subpackage    Centrora Security Firewall
 * @subpackage    Open Source Excellence WordPress Firewall
 * @author        Open Source Excellence {@link http://www.opensource-excellence.com}
 * @author        Created on 01-Jun-2013
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 *
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  @Copyright Copyright (C) 2008 - 2012- ... Open Source Excellence
 */
if (!defined('OSE_FRAMEWORK') && !defined('OSE_ADMINPATH') && !defined('_JEXEC'))
{
 die('Direct Access Not Allowed');
}
?>

<script type="text/javascript">
/*<![CDATA[*/
if(typeof(console)=='object')
{
	console.<?php echo $this->collapsedInFireBug?'groupCollapsed':'group'; ?>("Application Log");
<?php
foreach($data as $index=>$log)
{
	$time=date('H:i:s.',$log[3]).sprintf('%03d',(int)(($log[3]-(int)$log[3])*1000));
	if($log[1]===CLogger::LEVEL_WARNING)
		$func='warn';
	elseif($log[1]===CLogger::LEVEL_ERROR)
		$func='error';
	else
		$func='log';
	$content=CJavaScript::quote("[$time][$log[1]][$log[2]] $log[0]");
	echo "\tconsole.{$func}(\"{$content}\");\n";
}
?>
	console.groupEnd();
}
/*]]>*/
</script>