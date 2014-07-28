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

$fallback = false;

$messages = array(

'HTMLPurifier' => 'HTML Purifier',

// for unit testing purposes
'LanguageFactoryTest: Pizza' => 'Pizza',
'LanguageTest: List' => '$1',
'LanguageTest: Hash' => '$1.Keys; $1.Values',

'Item separator' => ', ',
'Item separator last' => ' and ', // non-Harvard style

'ErrorCollector: No errors' => 'No errors detected. However, because error reporting is still incomplete, there may have been errors that the error collector was not notified of; please inspect the output HTML carefully.',
'ErrorCollector: At line'   => ' at line $line',
'ErrorCollector: Incidental errors'  => 'Incidental errors',

'Lexer: Unclosed comment'      => 'Unclosed comment',
'Lexer: Unescaped lt'          => 'Unescaped less-than sign (<) should be &lt;',
'Lexer: Missing gt'            => 'Missing greater-than sign (>), previous less-than sign (<) should be escaped',
'Lexer: Missing attribute key' => 'Attribute declaration has no key',
'Lexer: Missing end quote'     => 'Attribute declaration has no end quote',
'Lexer: Extracted body'        => 'Removed document metadata tags',

'Strategy_RemoveForeignElements: Tag transform'              => '<$1> element transformed into $CurrentToken.Serialized',
'Strategy_RemoveForeignElements: Missing required attribute' => '$CurrentToken.Compact element missing required attribute $1',
'Strategy_RemoveForeignElements: Foreign element to text'    => 'Unrecognized $CurrentToken.Serialized tag converted to text',
'Strategy_RemoveForeignElements: Foreign element removed'    => 'Unrecognized $CurrentToken.Serialized tag removed',
'Strategy_RemoveForeignElements: Comment removed'            => 'Comment containing "$CurrentToken.Data" removed',
'Strategy_RemoveForeignElements: Foreign meta element removed' => 'Unrecognized $CurrentToken.Serialized meta tag and all descendants removed',
'Strategy_RemoveForeignElements: Token removed to end'       => 'Tags and text starting from $1 element where removed to end',
'Strategy_RemoveForeignElements: Trailing hyphen in comment removed' => 'Trailing hyphen(s) in comment removed',
'Strategy_RemoveForeignElements: Hyphens in comment collapsed' => 'Double hyphens in comments are not allowed, and were collapsed into single hyphens',

'Strategy_MakeWellFormed: Unnecessary end tag removed' => 'Unnecessary $CurrentToken.Serialized tag removed',
'Strategy_MakeWellFormed: Unnecessary end tag to text' => 'Unnecessary $CurrentToken.Serialized tag converted to text',
'Strategy_MakeWellFormed: Tag auto closed'             => '$1.Compact started on line $1.Line auto-closed by $CurrentToken.Compact',
'Strategy_MakeWellFormed: Tag carryover'               => '$1.Compact started on line $1.Line auto-continued into $CurrentToken.Compact',
'Strategy_MakeWellFormed: Stray end tag removed'       => 'Stray $CurrentToken.Serialized tag removed',
'Strategy_MakeWellFormed: Stray end tag to text'       => 'Stray $CurrentToken.Serialized tag converted to text',
'Strategy_MakeWellFormed: Tag closed by element end'   => '$1.Compact tag started on line $1.Line closed by end of $CurrentToken.Serialized',
'Strategy_MakeWellFormed: Tag closed by document end'  => '$1.Compact tag started on line $1.Line closed by end of document',

'Strategy_FixNesting: Node removed'          => '$CurrentToken.Compact node removed',
'Strategy_FixNesting: Node excluded'         => '$CurrentToken.Compact node removed due to descendant exclusion by ancestor element',
'Strategy_FixNesting: Node reorganized'      => 'Contents of $CurrentToken.Compact node reorganized to enforce its content model',
'Strategy_FixNesting: Node contents removed' => 'Contents of $CurrentToken.Compact node removed',

'AttrValidator: Attributes transformed' => 'Attributes on $CurrentToken.Compact transformed from $1.Keys to $2.Keys',
'AttrValidator: Attribute removed' => '$CurrentAttr.Name attribute on $CurrentToken.Compact removed',

);

$errorNames = array(
    E_ERROR   => 'Error',
    E_WARNING => 'Warning',
    E_NOTICE  => 'Notice'
);

// vim: et sw=4 sts=4
