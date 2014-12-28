<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2014 - 2015 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

abstract class ModWoWDemoHelper
{
    /**
     * @return array
     */
    public static function getGuilds()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select('*')
            ->from('#__wow_demo')
            ->order('region')
            ->order('realm')
            ->order('guild');

        $db->setQuery($query);

        $result = $db->loadObjectList();

        if (empty($result)) {
            return array();
        }

        $guilds = array();
        foreach ($result as $row) {
            $guilds[$row->region][$row->realm][] = $row->guild;
        }

        return $guilds;
    }

    public static function getForm()
    {
        $form = JForm::getInstance('wow_demo', __DIR__ . '/forms/default.xml');

        $data = JFactory::getSession()->get('wow_demo', new Joomla\Registry\Registry);

        $form->bind($data->toArray());

        return $form;
    }

    public static function setSessionData()
    {
        $app = JFactory::getApplication();

        if (
            $app->input->post->getVar('guild') &&
            $app->input->post->getVar('realm') &&
            $app->input->post->getVar('region') &&
            $app->input->post->getVar('locale') &&
            (
                $app->input->post->getVar('link') == 'wowhead.com' ||
                $app->input->post->getVar('link') == 'battle.net'
            )
        ) {
            $data = new Joomla\Registry\Registry(JPluginHelper::getPlugin('system', 'wow')->params);
            $data->set('guild', $app->input->post->getVar('guild'));
            $data->set('realm', $app->input->post->getVar('realm'));
            $data->set('region', $app->input->post->getVar('region'));
            $data->set('locale', $app->input->post->getVar('locale'));
            $data->set('link', $app->input->post->getVar('link'));

            $check = self::checkGuildExists($data);

            if (!is_object($check)) {
                self::redirect($check, 'error');
            }

            if ($check->code != 200) {
                self::redirect(JText::sprintf('MOD_WOW_DEMO_SWITCHED_ERR', $check->body->reason, $check->body->status), 'error');
            }

            JFactory::getSession()->set('wow_demo', $data);

            self::redirect(JText::sprintf('MOD_WOW_DEMO_SWITCHED', $check->body->name, $check->body->realm, strtoupper($data->get('region'))), 'message');
        }
    }

    private static function redirect($msg, $type)
    {
        JFactory::getApplication()->redirect('index.php', $msg, $type);
    }

    /**
     * @param Joomla\Registry\Registry $params
     * @return JHttpResponse|string
     */
    public static function checkGuildExists(Joomla\Registry\Registry $params)
    {
        try {
            $result = WoW::getInstance($params)->getAdapter('WoWAPI')->getData('guild', true);
        } catch (Exception $e) {
            return $e->getMessage();
        }

        if (isset($result->body->name) && isset($result->body->realm)) {
            $db = JFactory::getDbo();
            $sql = 'REPLACE INTO `#__wow_demo` SET `guild` = ' . $db->quote($result->body->name) . ', `realm` = ' . $db->quote($result->body->realm) . ', `region` = ' . $db->quote($params->get('region'));
            $db->setQuery($sql)->execute();
        }

        return $result;
    }
}