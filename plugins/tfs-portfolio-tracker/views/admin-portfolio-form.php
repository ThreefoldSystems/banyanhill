<?php if ( !is_wp_error($content['portfolios']) && !empty($content['portfolios']) ) : ?>
    <h3>Portfolio Configuration</h3>
    <div id="ts_accordion">
        <?php foreach ($content['portfolios'] as $portfolio) : ?>
            <h3><?php echo $portfolio->Name ?></h3>
            <div>
                <p>
                    <table>
                    <tbody>
                        <tr>
                            <td>
                                Portfolio ID:
                            </td>
                            <td>
                                <?php echo $portfolio->Id ?>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <p>There are no portfolios to display.</p>
<?php endif; ?>
