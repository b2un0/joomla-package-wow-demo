<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2013 - 2014 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class KunenaAvatarWoW_Demo extends KunenaAvatarWoW_Avatar
{
    protected function _getURL($user, $sizex, $sizey)
    {
        $user = KunenaFactory::getUser($user);

        try {
            $result = WoW::getInstance()->getAdapter('WoWAPI')->getData('members');
        } catch (Exception $e) {
            return $e->getMessage();
        }

        if (!is_array($result->body->members)) {
            return $this->wow_avatar->default;
        }

        // delete inactive members
        foreach ($result->body->members as $key => $member) {
            if (!isset($member->character->spec)) {
                unset($result->body->members[$key]);
            }
        }

        shuffle($result->body->members);

        if (!$user->isAdmin()) {
            $user->name = $result->body->members[random_int(0, count($result->body->members) - 1)]->character->name;
        }

        return parent::_getURL($user, $sizex, $sizey);
    }
}
