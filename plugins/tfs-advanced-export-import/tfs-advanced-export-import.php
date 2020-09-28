<?php
/**
 * Plugin Name: TFS Advanced Export/Import
 * Description: Adds an TFS Export/Import Pages that allows exporting specific content and exporting/importing pubcodes.
 * Version: 1.0
 * Author: Threefold Systems
 * Author URI: http://threefoldsystems.com
 */

// Settings' includes
require( 'classes/class-tfs-export-posts.php' );
require( 'classes/class-tfs-export-pubcode-connections.php' );
require( 'classes/class-tfs-import-pubcode-connections.php' );
require( 'classes/class-tfs-export-taxonomy-connections.php' );
require( 'classes/class-tfs-import-taxonomy-connections.php' );

/**
 * Class TFS_Advanced_Export_Import
 */
class TFS_Advanced_Export_Import
{
    /**
     * TFS_Advanced_Export_Import constructor.
     */
    public function __construct()
    {
        // Add menu item for admin page
        add_action( 'admin_menu', array( $this, 'admin_menu_item' ) );

        // Post export by date
        $this->export_posts = new TFS_Export_Posts();

        // Post pubcode connections export by date
        $this->export_pubcode_connections = new TFS_Export_Pubcode_Connections();

        // Post pubcode connections import
        $this->import_pubcode_connections = new TFS_Import_Pubcode_Connections();

        // Post taxonomy connections export
        $this->export_taxonomy_connections = new TFS_Export_Taxonomy_Connections();

        // Post taxonomy connections import
        $this->import_taxonomy_connections = new TFS_Import_Taxonomy_Connections();
    }

    /**
     * Add menu item for admin page.
     *
     * @return void
     */
    public function admin_menu_item()
    {
        add_menu_page(
            'TFS Export/Import',
            'TFS Export/Import',
            'manage_options',
            'tfs_ra_export_import',
            array( $this, 'export_import' ),
            'dashicons-migrate',
            11
        );
    }

    /**
     * Import/Export documentation page
     */
    public function export_import() {
        ?>
        <div class="wrap">
            <h2>TFS Export/Import</h2>
            <p><strong>Documentation</strong></p>
            </div>
        <?php
    }
}

new TFS_Advanced_Export_Import;