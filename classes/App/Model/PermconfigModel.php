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
		$panel = new panel ();

        if (isset($_REQUEST['dir']) && !empty($_REQUEST['dir'])){
            $path = urldecode( OSE_ABSPATH ) . urldecode( $_REQUEST['dir'] );
        } else {$path = urldecode( OSE_ABSPATH );}

		$return = $panel->getDirFileList($path);

		return $return;
	}
    public function  getFileTree(){
        $path = urldecode( OSE_ABSPATH ) .  urldecode( $_REQUEST['dir'] );

        // Create recursive dir iterator which skips dot folders and Flatten the recursive iterator
        $it  = 	new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::SELF_FIRST
                );
        // keep to the base folder
        $it->setMaxDepth(0);

        $files_array = array();
        //array to sort by folder
        foreach ($it as $fileinfo) {
            if ($fileinfo->isDir()) {
                $key = $fileinfo->getRealPath();
                $data = $fileinfo->getFilename();
                $files_array[$key] = $data;
            }
        }
        ksort($files_array);

        $list = '<ul id="filetreelist" class="filetree" style="display: none;">';

        if (($_REQUEST['dir']) == '/') {
            $list .= '<li class="folder collapsed" id="/"><a href="#" rel="/">ROOT</a></li>';
        }

        foreach($files_array as $key => $fileinfo){
            $rel = htmlentities( str_replace(OSE_ABSPATH, "", $key ));
            //if ($fileinfo->isDir()) {
                $list .= '<li class="folder collapsed" id="' . $rel . '"><a href="#" rel="' . $rel . '/">' . htmlentities( $fileinfo ) . '</a></li>';
            //}
        }
        $list .= '</ul>';
        echo ($list);
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
