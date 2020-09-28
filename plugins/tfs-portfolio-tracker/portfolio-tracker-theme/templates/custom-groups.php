<?php

$categories = array();
$group = $content['config']['group'];

foreach ($content['portfolios'] as $key => $value) {
    if ($value->TradeGroup->Name == $group) {
        if ($value->TradeGroup->{'$id'} !== null) {
            $newCategory = array('name' => $value->TradeGroup->Name, 'id' => $value->TradeGroup->{'$id'}, 'valid' => false);
            if ($value->TradeStatus != 2) {
                $newCategory['valid'] = true;
            }
            $categories[] = $newCategory;
        }
    }
}

foreach ($categories as $category): ?>
    <?php if ($category['valid']) : ?>
        <h2><span class="header-portfolio"><?php echo $category['name'] ?></span></h2>
        <br>
        <div>
        <table class="display compact portfolio-table" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Symbol</th>
            <th>Buy Date</th>
            <th>Curr Price</th>
            <th>Return</th>
            <th>Yield</th>
            <th>Stop Loss</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($content['portfolios'] as $portfolio) :
            if ($portfolio->TradeGroup->{'$id'} == $category['id'] || $portfolio->TradeGroup->{'$ref'} == $category['id']):
                ?>
                <?php
                if (isset($portfolio->OpenDate)) {
                    $opendate = date("m/d/Y",
                        strtotime(substr($portfolio->OpenDate, 0, strpos($portfolio->OpenDate, 'T'))));
                }
                ?>
                <tr>
                    <?php echo '<td>' . $portfolio->Name . '</td>'; ?>
                    <?php echo '<td>' . $portfolio->Symbol . '</td>'; ?>
                    <?php echo '<td>' . (isset($opendate) ? $opendate : null) . '</a></td>'; ?>
                    <?php echo '<td>' . "$" . number_format($portfolio->CurrentPrice, 2, '.', '') . '</td>'; ?>
                    <?php echo '<td>' . number_format($portfolio->Gain * 100, 1, '.', '') . '%</td>' ?>
                    <?php echo '<td>' . "$" . $portfolio->DividendYield . '</td>' ?>
                    <?php echo '<td>' . $portfolio->PositionSetting->Text1 . '</td>' ?>
                    <?php echo '<td>' . $portfolio->PositionSetting->Text2 . '%</td>' ?>
                </tr>
            <?php else :
                continue;
            endif;
        endforeach; ?>
    <?php endif; ?>
    </tbody>
    </table>
    </div>
<?php endforeach;