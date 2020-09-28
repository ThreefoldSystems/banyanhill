<div id="mobile-portfolio-view">
    <?php
    $i = 1;
    if(!is_wp_error($content['portfolios'])) :
        foreach($content['portfolios'] as $portfolio) :
            if(isset($portfolio->OpenDate))
                $opendate = date("m/d/Y", strtotime(substr($portfolio->OpenDate, 0, strpos($portfolio->OpenDate, 'T'))));
            ?>
            <div class="portfolio-header <?php echo ($i % 2) == 0 ? 'even' : 'odd'; ?>" >
                <p class = "portfolio-name"><?php echo $portfolio->Name; ?></p>
                <p class = "portfolio-symbol"><?php echo $portfolio->Symbol; ?></p>
                <p class = "portfolio-toggle" data-portfolio="<?php echo $portfolio->Symbol; ?>">
                    <span><i class = 'fa fa-plus'></i></span>
                    <span><i class = 'fa fa-minus'></i></span>
                </p>
            </div>
            <div class="portfolio-content" data-portfolio="<?php echo $portfolio->Symbol; ?>">
                <p>
                    <span class = "portfolio-title">Description: </span>
                    <a href="<?php echo $portfolio->PositionSetting->OpenDateLink; ?>"><?php $portfolio->PositionSetting->Text2; ?></a>
                </p>
                <p>
                    <span class = "portfolio-title">Open Date: </span>
                    <?php echo (isset($opendate) ? $opendate : null); ?>
                </p>
                <p>
                    <span class = "portfolio-title">Open Price: </span>
                    <?php echo isset($portfolio->OpenPrice) ? $portfolio->CurrencySygn . number_format($portfolio->OpenPrice, 2, '.', '') : ''; ?>
                </p>
                <p>
                    <span class = "portfolio-title">Recent Price: </span>
                    <?php echo isset($portfolio->CurrentPrice) ? $portfolio->CurrencySygn . number_format($portfolio->CurrentPrice, 2, '.', '') : ''; ?>
                </p>
                <p>
                    <span class = "portfolio-title">Dividends: </span>
                    <?php echo isset($portfolio->DividendsPrice) ? $portfolio->CurrencySygn . number_format($portfolio->Dividends, 2, '.', '') : ''; ?>
                </p>
                <p>
                    <span class = "portfolio-title">Returns: </span>
                    <?php echo number_format($portfolio->Gain * 100, 1, '.', '') . '%'; ?>
                </p>
                <p class="notes-container"><span class = "portfolio-title">Advice: </span><?php echo $portfolio->PositionSetting->Text1; ?></p>
            </div>
            <?php
            $i ++;
        endforeach; ?>
    <?php endif; ?>
</div>

<table id="pttable" class="display compact" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Company</th>
        <th>Symbol</th>
        <th>Description</th>
        <th>Open Date</th>
        <th>Open Price</th>
        <th>Recent Price</th>
        <th>Dividends</th>
        <th>Returns</th>
        <th>Advice</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if(!is_wp_error($content['portfolios'])) :
        foreach($content['portfolios'] as $portfolio) :
                if(isset($portfolio->OpenDate))
                    $opendate = date("m/d/Y", strtotime(substr($portfolio->OpenDate, 0, strpos($portfolio->OpenDate, 'T'))));
                ?>
                <tr>
                    <?php echo '<td>' . $portfolio->Name . '</td>'; ?>
                    <?php echo '<td>' . $portfolio->Symbol . '</td>'; ?>
                    <?php echo '<td><a href="' . $portfolio->PositionSetting->OpenDateLink . '">' . $portfolio->PositionSetting->Text2 . '</a></td>'; ?>
                    <?php echo '<td>' . (isset($opendate) ? $opendate : null) . '</td>'; ?>
                    <?php echo '<td>' . (isset($portfolio->OpenPrice) ? $portfolio->CurrencySygn . number_format($portfolio->OpenPrice, 2, '.', '') : ''). '</td>'; ?>
                    <?php echo '<td>' . (isset($portfolio->CurrentPrice) ? $portfolio->CurrencySygn . number_format($portfolio->CurrentPrice, 2, '.', '') : ''). '</td>'; ?>
                    <?php echo '<td>' . (isset($portfolio->DividendsPrice) ? $portfolio->CurrencySygn . number_format($portfolio->Dividends, 2, '.', '') : ''). '</td>'; ?>
                    <?php echo '<td>' . number_format($portfolio->Gain * 100, 1, '.', '') .  '%</td>'; ?>
                    <?php echo '<td>' . $portfolio->PositionSetting->Text1 . '</td>'; ?>
                </tr>
            <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>