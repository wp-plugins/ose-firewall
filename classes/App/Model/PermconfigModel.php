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
if (!defined('OSE_FRAMEWORK') && !defined('OSEFWDIR') && !defined('_JEXEC'))
{
	die('Direct Access Not Allowed');
}
require_once('BaseModel.php');
class PermconfigModel extends BaseModel 
{
	public function __construct(){
		//$this->loadDatabase ();
		oseFirewall::callLibClass('panel','panel');
	}
	
	public function loadLocalScript(){
		$this->loadAllAssets ();
		oseFirewall::loadJSFile ('CentroraPermconfig', 'permconfig.js', false);
	}

	public function getCHeader(){
		return oLang::_get('PERMCONFIG');
	}
	
	public function getCDescription(){
		return oLang::_get('PERMCONFIG_DESC');
	}

    public function getDirFileList(){
        $filearray = array();
        if (class_exists('SConfig')) {
            $rootpath = dirname(OSE_ABSPATH);
        } else {
            $rootpath = OSE_ABSPATH;
        }
        if (isset($_REQUEST['dir']) && !empty($_REQUEST['dir'])) {
            $path = $rootpath . urldecode($_REQUEST['dir']);
        } else {
            $path = $rootpath;
        }
        try{
            // Create recursive dir iterator which skips dot folders and Flatten the recursive iterator, folders come before their files
            $it = new RecursiveIteratorIterator
                    ( new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
                        RecursiveIteratorIterator::SELF_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
                    );
            // keep to the base folder
            $it->setMaxDepth(0);
            if ($it->valid()) {
                foreach ($it as $fileinfo) {
                    if (class_exists('SConfig')){
                        $newfileinfo = new SplFileInfo($fileinfo-> getRealPath() . ODS.'public_html');
                        if (is_readable($newfileinfo-> getRealPath()) && (urldecode($_REQUEST['dir']) == '/')) {
                            $parentfolder = $fileinfo->getfilename() .'/';
                            $filearray['data'][] = self::getfileinfo($newfileinfo, $rootpath, $parentfolder);
                        } elseif(!is_readable($newfileinfo-> getRealPath()) && is_readable($fileinfo-> getRealPath()) && (urldecode($_REQUEST['dir']) != '/')) {
                            $filearray['data'][] = self::getfileinfo($fileinfo, $rootpath);
                        }
                    } elseif (is_readable($fileinfo-> getRealPath()) && !class_exists('SConfig')){
                        $filearray['data'][] = self::getfileinfo($fileinfo, $rootpath);
                    }
                }
            } else {
                $filearray = array("draw" => 1, "recordsTotal" => "0", "recordsFiltered" => "0", "data" => array());
            }
            return $filearray;
        } catch (Exception $e) {
            return $filearray = array("draw" => 1, "recordsTotal" => "0", "recordsFiltered" => "0", "data" => array());
        }
    }
    private function  getfileinfo ($fileinfo, $rootpath, $parentfolder = null){
        if ($fileinfo->isDir()) {
            $filearray = array('path' => str_replace($rootpath, "", $fileinfo->getRealPath()),
                'name' => $parentfolder . $fileinfo->getfilename(),
                'type' => $fileinfo->getType(),
                'groupowner' => $fileinfo->getOwner() . ":" . $fileinfo->getGroup(),
                'perm' => substr(sprintf('%o', $fileinfo->getPerms()), -4),
                'icon' => "<img src='" . OSE_FWPUBLICURL . "/images/filetree/folder.png' alt='dir' />",
                'dirsort' => 1);
        } elseif ($fileinfo->isFile()) {
            $ext_code = strtolower(pathinfo($fileinfo->getFilename(), PATHINFO_EXTENSION));
            if (strpos('css,db,doc,file,film,flash,html,java,linux,music,pdf,application,code,directory,folder_open,spinner,php,picture,ppt,psd,ruby,script,txt,xls,xml,zip', $ext_code) == false) {
                $ext_code = 'file';
            }
            $filearray = array('path' => str_replace($rootpath, "", $fileinfo->getRealPath()),
                'name' => $parentfolder . $fileinfo->getfilename(),
                'type' => pathinfo($fileinfo->getFilename(), PATHINFO_EXTENSION), // $fileinfo->getExtension() for 5.3.6 onwards
                'groupowner' => $fileinfo->getOwner() . ":" . $fileinfo->getGroup(),
                'perm' => substr(sprintf('%o', $fileinfo->getPerms()), -4),
                'icon' => "<img src='" . OSE_FWPUBLICURL . "/images/filetree/" . $ext_code . ".png' alt='" . $ext_code . "' />",
                'dirsort' => 2);
        }
        return $filearray;
    }
    public function  getFileTree(){
        if (class_exists('SConfig')){
            $rootpath = dirname(OSE_ABSPATH );
        }else {
            $rootpath = OSE_ABSPATH;
        }
        $path = $rootpath .  urldecode( $_REQUEST['dir'] );

        $panel = new panel();
        $panel->getFileTree($rootpath, $path);
    }

    public function editPerms(){
        if ((isset($_REQUEST['chmodpaths']) && !empty($_REQUEST['chmodpaths'])) && (isset($_REQUEST['chmodbinary']) && !empty($_REQUEST['chmodbinary']))) {
            $chmodpathstringarray = $_REQUEST['chmodpaths'];
            $chmodpaths = explode('{/@^}', $chmodpathstringarray); //create array of files from post: delimiter = {/@^}
            $chmodbinary = $_REQUEST['chmodbinary'];
            if (isset($_REQUEST['recuroption']) && !empty($_REQUEST['recuroption'])) {
                $recuroption = $_REQUEST['recuroption'];
            } else {
                $recuroption = 'notset';
            }
        }
        /*fix $chmodbinary if string*/
        if (is_string($chmodbinary)) {
            $chmodbinary = octdec($chmodbinary);
            if (($chmodbinary <= 0) || ($chmodbinary > 0777)) {
                $chmodbinary = 0755;
            }
        }
        return $this->recurseeditPerms($chmodpaths, $chmodbinary, $recuroption);
    }
    /**
     * @param array $chmodpaths
     * @param string $chmodbinary
     * @param string $recuroption
     * @return array
     */
    public function recurseeditPerms ($chmodpaths, $chmodbinary, $recuroption){
        $resultarray = array();
        $resultarray['errors'] = '';
        /*for each item in $chmodpaths run the appropriate chmod*/
        foreach ($chmodpaths as $chmodpath){
            switch ($recuroption) {
                case "recurall":
                    if (strpos($chmodpath, 'dir:') !== false){
                        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(str_replace("dir:", "",OSE_ABSPATH.$chmodpath)));
                        foreach($it as $fileinfo) {
                            $ret = @chmod($fileinfo->getRealPath(), $chmodbinary);
                            if(!$ret) {$resultarray['errors'][] = str_replace(OSE_ABSPATH, "", $fileinfo->getRealPath());}
                        }
                    } else {
                        $ret = @chmod(str_replace("dir:", "",OSE_ABSPATH.$chmodpath), $chmodbinary);
                        if(!$ret) {$resultarray['errors'][] = $chmodpath;}
                    }
                    break;
                case "recurfiles":
                    if (strpos($chmodpath, 'dir:') !== false){
                        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(str_replace("dir:", "",OSE_ABSPATH.$chmodpath)));
                        foreach($it as $fileinfo) {
                            if ($fileinfo->isFile()) {
                                $ret = @chmod($fileinfo->getRealPath(), $chmodbinary);
                                if(!$ret) {$resultarray['errors'][] = str_replace(OSE_ABSPATH, "", $fileinfo->getRealPath());}
                            }
                        }
                    } else {
                        $ret = @chmod(str_replace("dir:", "",OSE_ABSPATH.$chmodpath), $chmodbinary);
                        if(!$ret) {$resultarray['errors'][] = $chmodpath;}
                    }
                    break;
                case "recurfolders":
                    if (strpos($chmodpath, 'dir:') !== false){
                        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(str_replace("dir:", "", OSE_ABSPATH.$chmodpath)));
                        foreach ($it as $fileinfo) {
                            if ($fileinfo->isDir()) {
                                $ret = @chmod($fileinfo->getRealPath(), $chmodbinary);
                                if (!$ret) {$resultarray['errors'][] = str_replace(OSE_ABSPATH, "", $fileinfo->getRealPath());}
                            }
                        }
                    }
                    break;
                case "notset":
                    $ret = (@chmod(str_replace("dir:", "",OSE_ABSPATH.$chmodpath), $chmodbinary));
                    if(!$ret) {$resultarray['errors'][] = $chmodpath;}
                    break;
            }
            //set all selected files/folders regardless of recursivity omit when recurfiles
            if ($recuroption !== "recurfiles" && $recuroption !== "notset") {
                $ret = (@chmod(str_replace("dir:", "", OSE_ABSPATH . $chmodpath), $chmodbinary));
                if (!$ret) { $resultarray['errors'][] = $chmodpath; }
            }
        }

        if ($resultarray['errors'] == '') {
            $resultarray['result'] = 1;
        }else{
            $resultarray['result'] = 0;
        }
        //print_r($resultarray); exit;
        return $resultarray;
	}

    public function oneClickFixPerm (){
        if (OSE_CMS == 'wordpress') {
            $Filesarraylist = array('/wp-config.php');
        }
        elseif (OSE_CMS == 'joomla') {
            $Filesarraylist = array('/configuration.php');
        }

        //$dirperms = 0755;
        $fileperms = 0644;

        return $this->recurseeditPerms($Filesarraylist, $fileperms, 'noCase');
    }
}
