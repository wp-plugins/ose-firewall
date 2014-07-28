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
 * CGettextPoFile class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CGettextPoFile represents a PO Gettext message file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.i18n.gettext
 * @since 1.0
 */
class CGettextPoFile extends CGettextFile
{
	/**
	 * Loads messages from a PO file.
	 * @param string $file file path
	 * @param string $context message context
	 * @return array message translations (source message => translated message)
	 */
	public function load($file,$context)
	{
		$pattern='/(msgctxt\s+"(.*?(?<!\\\\))")?'
			. '\s+msgid\s+"(.*?(?<!\\\\))"'
			. '\s+msgstr\s+"(.*?(?<!\\\\))"/';
		$content=file_get_contents($file);
        $n=preg_match_all($pattern,$content,$matches);
        $messages=array();
        for($i=0;$i<$n;++$i)
        {
        	if($matches[2][$i]===$context)
        	{
	        	$id=$this->decode($matches[3][$i]);
	        	$message=$this->decode($matches[4][$i]);
	        	$messages[$id]=$message;
	        }
        }
        return $messages;
	}

	/**
	 * Saves messages to a PO file.
	 * @param string $file file path
	 * @param array $messages message translations (message id => translated message).
	 * Note if the message has a context, the message id must be prefixed with
	 * the context with chr(4) as the separator.
	 */
	public function save($file,$messages)
	{
		$content='';
		foreach($messages as $id=>$message)
		{
			if(($pos=strpos($id,chr(4)))!==false)
			{
				$content.='msgctxt "'.substr($id,0,$pos)."\"\n";
				$id=substr($id,$pos+1);
			}
			$content.='msgid "'.$this->encode($id)."\"\n";
			$content.='msgstr "'.$this->encode($message)."\"\n\n";
		}
		file_put_contents($file,$content);
	}

	/**
	 * Encodes special characters in a message.
	 * @param string $string message to be encoded
	 * @return string the encoded message
	 */
	protected function encode($string)
	{
		return str_replace(array('"', "\n", "\t", "\r"),array('\\"', "\\n", '\\t', '\\r'),$string);
	}

	/**
	 * Decodes special characters in a message.
	 * @param string $string message to be decoded
	 * @return string the decoded message
	 */
	protected function decode($string)
	{
		return str_replace(array('\\"', "\\n", '\\t', '\\r'),array('"', "\n", "\t", "\r"),$string);
	}
}