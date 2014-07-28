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

class HTMLPurifier_Printer_CSSDefinition extends HTMLPurifier_Printer
{

    protected $def;

    public function render($config) {
        $this->def = $config->getCSSDefinition();
        $ret = '';

        $ret .= $this->start('div', array('class' => 'HTMLPurifier_Printer'));
        $ret .= $this->start('table');

        $ret .= $this->element('caption', 'Properties ($info)');

        $ret .= $this->start('thead');
        $ret .= $this->start('tr');
        $ret .= $this->element('th', 'Property', array('class' => 'heavy'));
        $ret .= $this->element('th', 'Definition', array('class' => 'heavy', 'style' => 'width:auto;'));
        $ret .= $this->end('tr');
        $ret .= $this->end('thead');

        ksort($this->def->info);
        foreach ($this->def->info as $property => $obj) {
            $name = $this->getClass($obj, 'AttrDef_');
            $ret .= $this->row($property, $name);
        }

        $ret .= $this->end('table');
        $ret .= $this->end('div');

        return $ret;
    }

}

// vim: et sw=4 sts=4
