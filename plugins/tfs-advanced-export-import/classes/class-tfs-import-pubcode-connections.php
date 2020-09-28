<?php
/**
 * Class TFS_Import_Pubcode_Connections
 *
 * Post pubcode connections import
 */
class TFS_Import_Pubcode_Connections
{
    public function __construct()
    {
        // Add menu item for admin page
        add_action( 'admin_menu', array( $this, 'admin_menu_item' ) );
    }

    /**
     * Add menu item for admin page.
     *
     * @return void
     */
    public function admin_menu_item()
    {
        add_submenu_page(
            'tfs_ra_export_import',
            'Import Pubcode Connections',
            'Import Pubcode Connections',
            'manage_options',
            'tfs_ra_pubcode_import',
            array( $this, 'pubcode_import_page' )
        );
    }

    /**
     * Page for pubcode import
     */
    public function pubcode_import_page() {
        ?>
        <div class="wrap">
            <h2>Import Pubcode Connections</h2>

            <?php
            // Check if MW auth plugins installed
            if ( class_exists( 'agora_authentication_plugin' ) ) {
                if (isset($_FILES['import_connections_file'])) {
                    $import_json = file_get_contents($_FILES['import_connections_file']['tmp_name']);

                    $import_connections_file = json_decode($import_json);


                    if ($import_connections_file && is_array($import_connections_file)) {
                        $total_success = 0;
                        $total_fail = 0;

                        foreach ($import_connections_file as $post_import) {
                            $update_post_pubcodes = wp_set_object_terms($post_import->post_id, $post_import->pubcodes, 'pubcode', true);

                            if (is_wp_error($update_post_pubcodes)) {
                                $total_fail++;
                            } else {
                                $total_success++;
                            }
                        }
                        ?>
                        <p>
                            Your post and pubcode connections have been imported.
                        </p>

                        <p><?php echo $total_success; ?> posts have been updated successfully
                            and <?php echo $total_fail; ?> posts have failed to update.</p>
                        <?php
                    } else {
                        ?>
                        <p>There was nothing to import.</p>
                        <?php
                    }
                } else {
                    ?>
                    <p>
                        Please select .json file that you have downloaded when you were exporting post and pubcode
                        connections.
                    </p>

                    <form name="import_connections" action="" method="post" enctype="multipart/form-data">
                        <input type="file" name="import_connections_file" accept="*.json">
                        <br/>
                        <br/>
                        <input class="button button-primary" type="submit" value="Import Connections"/>

                        <input type="hidden" name="page" value="tfs_ra_pubcode_import"/>

                        <input type="hidden" name="import" value="true"/>
                    </form>
                    <?php
                }
            } else {
                ?>
                <h3>Middleware Authentication plugins needs to be active for you to perform this action.</h3>
                <?php
            }
            ?>
        </div>
        <?php
    }
}
