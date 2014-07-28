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
 * CEmailLogRoute class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CEmailLogRoute sends selected log messages to email addresses.
 *
 * The target email addresses may be specified via {@link setEmails emails} property.
 * Optionally, you may set the email {@link setSubject subject}, the
 * {@link setSentFrom sentFrom} address and any additional {@link setHeaders headers}.
 *
 * @property array $emails List of destination email addresses.
 * @property string $subject Email subject. Defaults to CEmailLogRoute::DEFAULT_SUBJECT.
 * @property string $sentFrom Send from address of the email.
 * @property array $headers Additional headers to use when sending an email.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.logging
 * @since 1.0
 */
class CEmailLogRoute extends CLogRoute
{
	/**
	 * @var boolean set this property to true value in case log data you're going to send through emails contains
	 * non-latin or UTF-8 characters. Emails would be UTF-8 encoded.
	 * @since 1.1.13
	 */
	public $utf8=false;
	/**
	 * @var array list of destination email addresses.
	 */
	private $_email=array();
	/**
	 * @var string email subject
	 */
	private $_subject;
	/**
	 * @var string email sent from address
	 */
	private $_from;
	/**
	 * @var array list of additional headers to use when sending an email.
	 */
	private $_headers=array();

	/**
	 * Sends log messages to specified email addresses.
	 * @param array $logs list of log messages
	 */
	protected function processLogs($logs)
	{
		$message='';
		foreach($logs as $log)
			$message.=$this->formatLogMessage($log[0],$log[1],$log[2],$log[3]);
		$message=wordwrap($message,70);
		$subject=$this->getSubject();
		if($subject===null)
			$subject=Yii::t('yii','Application Log');
		foreach($this->getEmails() as $email)
			$this->sendEmail($email,$subject,$message);
	}

	/**
	 * Sends an email.
	 * @param string $email single email address
	 * @param string $subject email subject
	 * @param string $message email content
	 */
	protected function sendEmail($email,$subject,$message)
	{
		$headers=$this->getHeaders();
		if($this->utf8)
		{
			$headers[]="MIME-Version: 1.0";
			$headers[]="Content-type: text/plain; charset=UTF-8";
			$subject='=?UTF-8?B?'.base64_encode($subject).'?=';
		}
		if(($from=$this->getSentFrom())!==null)
		{
			$matches=array();
			preg_match_all('/([^<]*)<([^>]*)>/iu',$from,$matches);
			if(isset($matches[1][0],$matches[2][0]))
			{
				$name=$this->utf8 ? '=?UTF-8?B?'.base64_encode(trim($matches[1][0])).'?=' : trim($matches[1][0]);
				$from=trim($matches[2][0]);
				$headers[]="From: {$name} <{$from}>";
			}
			else
				$headers[]="From: {$from}";
			$headers[]="Reply-To: {$from}";
		}
		mail($email,$subject,$message,implode("\r\n",$headers));
	}

	/**
	 * @return array list of destination email addresses
	 */
	public function getEmails()
	{
		return $this->_email;
	}

	/**
	 * @param mixed $value list of destination email addresses. If the value is
	 * a string, it is assumed to be comma-separated email addresses.
	 */
	public function setEmails($value)
	{
		if(is_array($value))
			$this->_email=$value;
		else
			$this->_email=preg_split('/[\s,]+/',$value,-1,PREG_SPLIT_NO_EMPTY);
	}

	/**
	 * @return string email subject. Defaults to CEmailLogRoute::DEFAULT_SUBJECT
	 */
	public function getSubject()
	{
		return $this->_subject;
	}

	/**
	 * @param string $value email subject.
	 */
	public function setSubject($value)
	{
		$this->_subject=$value;
	}

	/**
	 * @return string send from address of the email
	 */
	public function getSentFrom()
	{
		return $this->_from;
	}

	/**
	 * @param string $value send from address of the email
	 */
	public function setSentFrom($value)
	{
		$this->_from=$value;
	}

	/**
	 * @return array additional headers to use when sending an email.
	 * @since 1.1.4
	 */
	public function getHeaders()
	{
		return $this->_headers;
	}

	/**
	 * @param mixed $value list of additional headers to use when sending an email.
	 * If the value is a string, it is assumed to be line break separated headers.
	 * @since 1.1.4
	 */
	public function setHeaders($value)
	{
		if (is_array($value))
			$this->_headers=$value;
		else
			$this->_headers=preg_split('/\r\n|\n/',$value,-1,PREG_SPLIT_NO_EMPTY);
	}
}