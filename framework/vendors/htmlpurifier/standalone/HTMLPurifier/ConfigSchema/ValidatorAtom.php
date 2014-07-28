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

/**
 * Fluent interface for validating the contents of member variables.
 * This should be immutable. See HTMLPurifier_ConfigSchema_Validator for
 * use-cases. We name this an 'atom' because it's ONLY for validations that
 * are independent and usually scalar.
 */
class HTMLPurifier_ConfigSchema_ValidatorAtom
{

    protected $context, $obj, $member, $contents;

    public function __construct($context, $obj, $member) {
        $this->context     = $context;
        $this->obj         = $obj;
        $this->member      = $member;
        $this->contents    =& $obj->$member;
    }

    public function assertIsString() {
        if (!is_string($this->contents)) $this->error('must be a string');
        return $this;
    }

    public function assertIsBool() {
        if (!is_bool($this->contents)) $this->error('must be a boolean');
        return $this;
    }

    public function assertIsArray() {
        if (!is_array($this->contents)) $this->error('must be an array');
        return $this;
    }

    public function assertNotNull() {
        if ($this->contents === null) $this->error('must not be null');
        return $this;
    }

    public function assertAlnum() {
        $this->assertIsString();
        if (!ctype_alnum($this->contents)) $this->error('must be alphanumeric');
        return $this;
    }

    public function assertNotEmpty() {
        if (empty($this->contents)) $this->error('must not be empty');
        return $this;
    }

    public function assertIsLookup() {
        $this->assertIsArray();
        foreach ($this->contents as $v) {
            if ($v !== true) $this->error('must be a lookup array');
        }
        return $this;
    }

    protected function error($msg) {
        throw new HTMLPurifier_ConfigSchema_Exception(ucfirst($this->member) . ' in ' . $this->context . ' ' . $msg);
    }

}

// vim: et sw=4 sts=4
