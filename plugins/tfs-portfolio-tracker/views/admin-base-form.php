<div class="grid_half">

    <form method="post" action="options.php">
        <?php settings_fields( $config_name . '_group' );?>
        <h3><?php _e('API Settings'); ?></h3>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="prod_token">
                        <?php _e('Token'); ?>
                    </label>
                </th>
                <td>
                    <input type="text" value="<?php echo $config['token']; ?>" id="token" name="<?php echo $config_name;?>[token]" class="regular-text" placeholder="API Token">
                    <p class="description"><?php _e('A random text string needed to authenticate with the Portfolio Tracker API'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="prod_url">
                        <?php _e('Portfolio URL');?>
                    </label>
                </th>
                <td>
                    <input type="text" value="<?php echo $config['url']; ?>" id="url" name="<?php echo $config_name;?>[url]" class="regular-text">
                    <p class="description"><?php _e('The URL for the Portfolio Tracker environment'); ?></p>
                </td>
            </tr>
            </tbody>
        </table>
        <h3><?php _e('Cache Settings'); ?></h3>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <?php _e('Cache Enabled:'); ?>
                </th>
                <td>
                    <div class="radio">
                        <label>
                            <input type="radio" name="<?php echo $config_name;?>[cache]" value="1" <?php if($config['cache'] == 1) echo 'checked'; ?>>
                            <?php _e('On'); ?>
                        </label>
                        <br>
                        <label>
                            <input type="radio" name="<?php echo $config_name;?>[cache]" value="0" <?php if($config['cache'] == 0) echo 'checked'; ?>>
                            <?php _e('Off'); ?>
                        </label>
                        <p class="description">
                            Note: disabling cache will drastically slow down page load times due to the size of the API calls
                        </p>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cache_time">
                        <?php _e('Cache Expiration');?>
                    </label>
                </th>
                <td>
                    <input type="text" value="<?php echo $config['hours']; ?>" id="hours" name="<?php echo $config_name;?>[hours]" class="small-text">
                    <label for="hours">Hours</label>
                    <input type="text" value="<?php echo $config['minutes']; ?>" id="minutes" name="<?php echo $config_name;?>[minutes]" class="small-text">
                    <label for="minutes">Minutes</label>
                    <p class="description"><?php _e('Set a time limit for the cached API calls'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cache_time">
                        <?php _e('Cache Refresh');?>
                    </label>
                </th>
                <td>
                    <button id="cache_clear" type="button" class="button button-primary">Clear Cache</button>
                    <span id="message" style="display: none;"></span>
                </td>
            </tr>
            </tbody>
        </table>

        <h3><?php _e('Logging Settings'); ?></h3>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <?php _e('Logging Enabled:'); ?>
                </th>
                <td>
                    <div class="radio">
                        <label>
                            <input type="radio" name="<?php echo $config_name;?>[logging]" value="1" <?php if($config['logging'] == 1) echo 'checked'; ?>>
                            <?php _e('On'); ?>
                        </label>
                        <br>
                        <label>
                            <input type="radio" name="<?php echo $config_name;?>[logging]" value="0" <?php if($config['logging'] == 0) echo 'checked'; ?>>
                            <?php _e('Off'); ?>
                        </label>
                        <p class="description">
                            Log events will be written to the default php error log
                        </p>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <?php submit_button(__( 'Save'), 'primary', 'submit'); ?>
    </form>
</div>

