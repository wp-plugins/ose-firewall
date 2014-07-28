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
 * CGettextMessageSource class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CGettextMessageSource represents a message source that is based on GNU Gettext.
 *
 * Each CGettextMessageSource instance represents the message tranlations
 * for a single domain. And each message category represents a message context
 * in Gettext. Translated messages are stored as either a MO or PO file,
 * depending on the {@link useMoFile} property value.
 *
 * All translations are saved under the {@link basePath} directory.
 * Translations in one language are kept as MO or PO files under an individual
 * subdirectory whose name is the language ID. The file name is specified via
 * {@link catalog} property, which defaults to 'messages'.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.i18n
 * @since 1.0
 */
class CGettextMessageSource extends CMessageSource
{
	const CACHE_KEY_PREFIX='Yii.CGettextMessageSource.';
	const MO_FILE_EXT='.mo';
	const PO_FILE_EXT='.po';

	/**
	 * @var integer the time in seconds that the messages can remain valid in cache.
	 * Defaults to 0, meaning the caching is disabled.
	 */
	public $cachingDuration=0;
	/**
	 * @var string the ID of the cache application component that is used to cache the messages.
	 * Defaults to 'cache' which refers to the primary cache application component.
	 * Set this property to false if you want to disable caching the messages.
	 */
	public $cacheID='cache';
	/**
	 * @var string the base path for all translated messages. Defaults to null, meaning
	 * the "messages" subdirectory of the application directory (e.g. "protected/messages").
	 */
	public $basePath;
	/**
	 * @var boolean whether to load messages from MO files. Defaults to true.
	 * If false, messages will be loaded from PO files.
	 */
	public $useMoFile=true;
	/**
	 * @var boolean whether to use Big Endian to read and write MO files.
	 * Defaults to false. This property is only used when {@link useMoFile} is true.
	 */
	public $useBigEndian=false;
	/**
	 * @var string the message catalog name. This is the name of the message file (without extension)
	 * that stores the translated messages. Defaults to 'messages'.
	 */
	public $catalog='messages';

	/**
	 * Initializes the application component.
	 * This method overrides the parent implementation by preprocessing
	 * the user request data.
	 */
	public function init()
	{
		parent::init();
		if($this->basePath===null)
			$this->basePath=Yii::getPathOfAlias('application.messages');
	}

	/**
	 * Loads the message translation for the specified language and category.
	 * @param string $category the message category
	 * @param string $language the target language
	 * @return array the loaded messages
	 */
	protected function loadMessages($category, $language)
	{
        $messageFile=$this->basePath . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . $this->catalog;
        if($this->useMoFile)
        	$messageFile.=self::MO_FILE_EXT;
        else
        	$messageFile.=self::PO_FILE_EXT;

		if ($this->cachingDuration > 0 && $this->cacheID!==false && ($cache=Yii::app()->getComponent($this->cacheID))!==null)
		{
			$key = self::CACHE_KEY_PREFIX . $messageFile;
			if (($data=$cache->get($key)) !== false)
				return unserialize($data);
		}

		if (is_file($messageFile))
		{
			if($this->useMoFile)
				$file=new CGettextMoFile($this->useBigEndian);
			else
				$file=new CGettextPoFile();
			$messages=$file->load($messageFile,$category);
			if(isset($cache))
			{
				$dependency=new CFileCacheDependency($messageFile);
				$cache->set($key,serialize($messages),$this->cachingDuration,$dependency);
			}
			return $messages;
		}
		else
			return array();
	}
}
