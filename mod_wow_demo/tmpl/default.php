<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2014 - 2015 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;
?>
<form action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="wow_demo" class="form-inline">

    <?php if (!empty($guilds)) : ?>
        <div class="control-group">
            <select id="wow_demo_guilds" class="selectpicker" data-style="btn-primary">
                <option value=""><?php echo JText::_('MOD_WOW_DEMO_SELECT'); ?></option>
                <?php foreach ($guilds as $region => $realms) : ?>
                    <optgroup label="<?php echo JText::_('MOD_WOW_DEMO_REGION_' . strtoupper($region)); ?>">

                        <?php foreach ($realms as $realm => $guilds) : ?>
                            <optgroup label="&nbsp;&nbsp;<?php echo $realm; ?>">
                                <?php foreach ($guilds as $guild) : ?>
                                    <option value="<?php echo $guild . ',' . $realm . ',' . $region; ?>">
                                        &nbsp;&nbsp;<?php echo $guild; ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>

                    </optgroup>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>

    <?php echo $form->getControlGroups('wow_demo'); ?>

    <div class="control-group">
        <input type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('MOD_WOW_DEMO_SWITCH'); ?>">
    </div>
</form>

<div class="modal hide" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <h1>Processing...</h1>
    </div>
    <div class="modal-body">
        <div class="progress progress-striped active">
            <div class="bar" style="width: 100%;"></div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function ($) {
        $('#wow_demo').on('submit', function () {
            $('#pleaseWaitDialog').modal();
        });

        $('#wow_demo_guilds').on('change', function () {
            var val = this.value.split(',');
            if (val.length == 3) {
                $('#wow_demo #guild').val(val[0]);
                $('#wow_demo #realm').val(val[1]);
                $('#wow_demo #region').val(val[2]);
            }
        });
    });
</script>
