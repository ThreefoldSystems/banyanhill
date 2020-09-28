<?php
$id = '$id';
$ref = '$ref';
$categories = [];
foreach ($content['portfolios'] as $portfolio) :
    if ($portfolio->TradeGroup->{$id} !== null) {
        $newCategory = array('name' => $portfolio->TradeGroup->Name, 'id' => $portfolio->TradeGroup->{$id}, 'valid' => false);
        if($portfolio->TradeStatus != 2){
            $newCategory['valid'] = true;
        }
        $categories[] = $newCategory;
    }
endforeach;

foreach ($categories as $category): ?>
    <?php if ($category['valid']) : ?>
        <h1><?php echo $category['name'] ?></h1>
        <table id="pttable" class="display compact" cellspacing="0" width="100%">
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
            if ($portfolio->TradeGroup->{$id} == $category['id'] || $portfolio->TradeGroup->{$ref} == $category['id']):
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
                    <?php echo '<td>' . 'N/A' . '</td>' ?>
                    <?php echo '<td>' . 'N/A' . '</td>' ?>
                    <?php echo '<td>' . 'N/A' . '%</td>' ?>
                </tr>
            <?php else :
                continue;
            endif;
        endforeach; ?>
    <?php endif; ?>
    </tbody>
    </table>
<?php endforeach;