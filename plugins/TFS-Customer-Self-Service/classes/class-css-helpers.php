<?php
/**
 * Helper functions for the plugin
 */
class CSS_Helpers
{
    /**
     * CSS_Helpers constructor.
     */
    public function __construct()
    {

    }

    /**
     *  Get remaining time
     *
     * @param $transient_name string Name of the transient
     *
     * @return string/bool
     */
    public function transient_time_remaining( $transient_name )
    {
        if ( $transient = get_transient( $transient_name ) ) {
            $remaining_time_in_minutes = 15 - round( abs( strtotime( current_time('H:i:s')) - strtotime( $transient ) ) / 60 );

            $remaining_time_in_minutes = $remaining_time_in_minutes == 0 ? '1' : $remaining_time_in_minutes;

            $minute = $remaining_time_in_minutes == 1 ? 'minute' : 'minutes';

            $remaining_time_in_minutes = $remaining_time_in_minutes . ' ' . $minute;

            return $remaining_time_in_minutes;
        }

        return false;
    }
}