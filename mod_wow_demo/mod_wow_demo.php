<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2014 - 2015 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

JLoader::register('ModWoWDemoHelper', __DIR__ . '/helper.php');

ModWoWDemoHelper::setSessionData();

$form = ModWoWDemoHelper::getForm();

$guilds = ModWoWDemoHelper::getGuilds();

require JModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default'));