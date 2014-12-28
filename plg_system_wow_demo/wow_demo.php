<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2014 - 2015 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class plgSystemWoW_Demo extends JPlugin
{
    public function onAfterInitialise()
    {
        $data = JFactory::getSession()->get('wow_demo', new Joomla\Registry\Registry);

        if (!class_exists('WoW')) {
            return false;
        }

        if (
            !$data->get('guild') ||
            !$data->get('realm') ||
            !$data->get('region') ||
            !$data->get('locale') ||
            !$data->get('link')
        ) {
            $params = new Joomla\Registry\Registry(JPluginHelper::getPlugin('system', 'wow')->params);

            $data = new Joomla\Registry\Registry;
            $data->set('guild', $params->get('guild'));
            $data->set('realm', $params->get('realm'));
            $data->set('region', $params->get('region'));
            $data->set('locale', $params->get('locale'));
            $data->set('link', $params->get('link'));

            JFactory::getSession()->set('wow_demo', $data);
        }

        WoW::getInstance()->params->set('guild', $data->get('guild'));
        WoW::getInstance()->params->set('realm', $data->get('realm'));
        WoW::getInstance()->params->set('region', $data->get('region'));
        WoW::getInstance()->params->set('locale', $data->get('locale'));
        WoW::getInstance()->params->set('link', $data->get('link'));
    }
}