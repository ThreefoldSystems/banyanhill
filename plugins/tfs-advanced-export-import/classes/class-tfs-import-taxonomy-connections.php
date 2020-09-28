<?php
/**
 * Class TFS_Import_Taxonomy_Connections
 *
 * Post taxonomy connections import
 */
class TFS_Import_Taxonomy_Connections
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
            'Import Taxonomy Connections',
            'Import Taxonomy Connections',
            'manage_options',
            'tfs_ra_taxonomy_import',
            array( $this, 'taxonomy_import_page' )
        );
    }

    /**
     * Page for taxonomy import
     */
    public function taxonomy_import_page() {
        ?>
        <div class="wrap">
            <h2>Import Taxonomy Connections</h2>

            <?php
            // Check if MW auth plugins installed
            if ( class_exists( 'agora_authentication_plugin' ) ) {
                if (isset($_FILES['import_connections_file_tax'])) {
                    $import_json = file_get_contents($_FILES['import_connections_file_tax']['tmp_name']);

                    $import_connections_file = json_decode($import_json);

                    if ($import_connections_file && is_array($import_connections_file)) {
                        foreach ($import_connections_file as $post_import) {
                            $post_for_import = get_post( $post_import->post_id );

                            if ( $post_for_import->post_type == 'archives' ) {
                                wp_set_post_terms( $post_import->post_id, $post_import->cats, 'archives-category' );
                            }
                        }

                        ?>
                        <p>
                            Your post and taxonomy connections have been imported.
                        </p>

                        <p>Posts have been updated successfully.</p>
                        <?php
                    } else {
                        ?>
                        <p>There was nothing to import.</p>
                        <?php
                    }
                } else {
                    ?>
                    <p>
                        Please select .json file that you have downloaded when you were exporting post and taxonomy
                        connections.
                    </p>

                    <form name="import_connections" action="" method="post" enctype="multipart/form-data">
                        <input type="file" name="import_connections_file_tax" accept="*.json">
                        <br/>
                        <br/>
                        <input class="button button-primary" type="submit" value="Import Connections"/>

                        <input type="hidden" name="page" value="tfs_ra_taxonomy_import"/>

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
