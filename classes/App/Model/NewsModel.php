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
class NewsModel extends BaseModel{
    public function __construct(){

    }

    public function getCHeader(){
        return oLang::_get('NEWS_TITLE');
    }

    public function getCDescription(){
        return oLang::_get('NEWS_DESC');
    }

    public function loadLocalScript(){
        $this->loadAllAssets();
        oseFirewall::loadJSFile ('CentroraNews', 'news.js', false);
    }

    private function getFeed($rssUrl, $limit){
        oseFirewall::callLibClass('panel','panel');
        $panel = new panel ();
        $panel->hasNewsRead(true, 'read');
        return $panel->getJSONFeed($rssUrl, $limit);
    }

    public function getAnyFeed ($rssUrl, $limit){
        $data = $this->getFeed($rssUrl, $limit);
        echo '<br />';
        foreach ($data->feed->entries as $entry) {
            echo '<p><strong><a target="_blank" href="' . $entry->link . '" title="' . $entry->title . '">' . $entry->title . '</a></strong><br /></p>';
            echo '<p>' . $entry->contentSnippet . '</p>';
        }
    }

    public function getchangelogFeed($rssUrl, $limit){
        $data = $this->getFeed($rssUrl, $limit);
        $reVersion = "/=(.*?)\\=/";
        $reList = "/^[*].*/m";
        $i = 0; $classname = 'hide';

        foreach ($data->feed->entries as $entry) {
            $str = strip_tags($entry->content);
            $classShow = ($i == 0) ? 'class="changelist expanded"' : 'class="changelist collapsed" style="display: none;"';
            preg_match_all($reVersion, $str, $verMatches);
            preg_match_all($reList, $str, $listMatches);

            echo '<div id="showmenu'.$i.'"><strong>';
            echo '<input id="btnshowmenu'.$i.'"class="btn btn-sm" type="button" onclick=toggleChangelist('.$i.'); title="Show Changelog" value="' . $verMatches[1][0] . '">';
            echo '</strong><br /></div>';
            echo '<div  id="changelist'.$i.'"'.$classShow.'><pre style="color:#484848">';
            foreach ($listMatches[0] as $list){
                echo  $list .'<br />';
            }
            echo '<p class="right"><a target="_blank" href="' . $entry->link . '" title="' . $entry->title . '">View Full - ' . $verMatches[1][0] . '</a></p>';
            echo '</pre></div><p></p>';
        $i++;
        }
    }
}