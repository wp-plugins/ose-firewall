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
 * Converts HTMLPurifier_ConfigSchema_Interchange to an XML format,
 * which can be further processed to generate documentation.
 */
class HTMLPurifier_ConfigSchema_Builder_Xml extends XMLWriter
{

    protected $interchange;
    private $namespace;

    protected function writeHTMLDiv($html) {
        $this->startElement('div');

        $purifier = HTMLPurifier::getInstance();
        $html = $purifier->purify($html);
        $this->writeAttribute('xmlns', 'http://www.w3.org/1999/xhtml');
        $this->writeRaw($html);

        $this->endElement(); // div
    }

    protected function export($var) {
        if ($var === array()) return 'array()';
        return var_export($var, true);
    }

    public function build($interchange) {
        // global access, only use as last resort
        $this->interchange = $interchange;

        $this->setIndent(true);
        $this->startDocument('1.0', 'UTF-8');
        $this->startElement('configdoc');
        $this->writeElement('title', $interchange->name);

        foreach ($interchange->directives as $directive) {
            $this->buildDirective($directive);
        }

        if ($this->namespace) $this->endElement(); // namespace

        $this->endElement(); // configdoc
        $this->flush();
    }

    public function buildDirective($directive) {

        // Kludge, although I suppose having a notion of a "root namespace"
        // certainly makes things look nicer when documentation is built.
        // Depends on things being sorted.
        if (!$this->namespace || $this->namespace !== $directive->id->getRootNamespace()) {
            if ($this->namespace) $this->endElement(); // namespace
            $this->namespace = $directive->id->getRootNamespace();
            $this->startElement('namespace');
            $this->writeAttribute('id', $this->namespace);
            $this->writeElement('name', $this->namespace);
        }

        $this->startElement('directive');
        $this->writeAttribute('id', $directive->id->toString());

        $this->writeElement('name', $directive->id->getDirective());

        $this->startElement('aliases');
            foreach ($directive->aliases as $alias) $this->writeElement('alias', $alias->toString());
        $this->endElement(); // aliases

        $this->startElement('constraints');
            if ($directive->version) $this->writeElement('version', $directive->version);
            $this->startElement('type');
                if ($directive->typeAllowsNull) $this->writeAttribute('allow-null', 'yes');
                $this->text($directive->type);
            $this->endElement(); // type
            if ($directive->allowed) {
                $this->startElement('allowed');
                    foreach ($directive->allowed as $value => $x) $this->writeElement('value', $value);
                $this->endElement(); // allowed
            }
            $this->writeElement('default', $this->export($directive->default));
            $this->writeAttribute('xml:space', 'preserve');
            if ($directive->external) {
                $this->startElement('external');
                    foreach ($directive->external as $project) $this->writeElement('project', $project);
                $this->endElement();
            }
        $this->endElement(); // constraints

        if ($directive->deprecatedVersion) {
            $this->startElement('deprecated');
                $this->writeElement('version', $directive->deprecatedVersion);
                $this->writeElement('use', $directive->deprecatedUse->toString());
            $this->endElement(); // deprecated
        }

        $this->startElement('description');
            $this->writeHTMLDiv($directive->description);
        $this->endElement(); // description

        $this->endElement(); // directive
    }

}

// vim: et sw=4 sts=4
