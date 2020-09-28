<div class="symbols">
    <div class="header"><?php echo $symbol_data->CompanyName; ?></div>

    <div class="clear"></div>

    <div class="chart-top">
        <?php echo $symbol_data->Exchange; ?>: <?php echo $symbol ?>
        <div class="date">
            <?php echo ( ( (int)date( "H", strtotime( $symbol_data->LatestDate ) ) == 0 )
                && ( (int)date( "i", strtotime( $symbol_data->LatestDate ) ) == 0 ) ) ?
                date( "M d", strtotime( $symbol_data->LatestDate ) ) : date( "M d h:i A", strtotime( $symbol_data->LatestDate ) );
            ?>
        </div>
    </div>

    <div class="chart-container">
        <div class="chart" data-datemark="<?php echo $symbol_data->LatestDate; ?>">
            <?php
            foreach ( $all_periods as $this_period ) {
                $active_chart    = $this_period == '5y' ? ' class="active"' : '';
                echo '<div data-chart="' . $this_period . '"' . $active_chart .'>'. $charts[$this_period] .'</div>';
            }
            ?>
        </div>
        <div class="period">
            <?php
            foreach ( $all_periods as $this_period ) {
                $active_period    = $this_period == '5y' ? ' class="active"' : '';
                echo '<span' . $active_period .'>' . $this_period . '</span>';
            }
            ?>
        </div>
    </div>

    <div class="chart-bottom">
        Price: <?php echo number_format( (float)$symbol_data->LatestPrice, 2 ); ?>
        | Ch:<?php echo number_format( (float)$symbol_data->Change, 2 ) ?>&nbsp;
        (<?php echo number_format( (float) $symbol_data->ChangePercent, 1) ?>%)
    </div>

</div>