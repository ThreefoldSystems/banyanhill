<div class="wrap">
    <h2><?php _e('Portfolio Tracker'); ?></h2>

<?php if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true'): ?>
    <div id="setting-error-settings_updated" class="updated settings-error">
        <p><strong><?php _e('Settings Saved'); ?></strong></p>
    </div>
<?php endif; ?>

<h2 class="nav-tab-wrapper">
    <?php foreach($content['menuItems'] as $item): ?>
        <a href="admin.php?page=<?php echo $item['page']; ?>" class="nav-tab <?php if ($_GET['page'] == $item['page']) echo 'nav-tab-active'; ?>">
            <?php echo $item['title']; ?>
        </a>
    <?php endforeach; ?>
</h2>