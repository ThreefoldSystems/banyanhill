<?php
/**
 * Copyright (C) Threefold systems - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */
namespace csd_ext;

class class_middleware
{
    /**
     * @var agora_core_framework
     */
    public $core;

    /**
     * Constructor method
     *
     * @method __construct
     *
     */
    public function __construct()
    {
        if( class_exists('agora_core_framework') ) {
            $this->core = \agora_core_framework::get_instance();
        }
    }


    /**
     * Determine the user's remaining liability for a subscription
     *
     * @method get_refund_amount
     *
     * @param string $sub_ref subref for the subscription
     * @param boolean $is_full does this subscription offer a full refund
     *
     * @return string/boolean Remaining liability if found, false if not found
     */
    public function get_refund_amount ( $sub_ref, $is_full ) {
        $customer_number = $this->core->user->get_customer_number();
        if ( !is_wp_error( $customer_number ) ) {
            $subscriptions = $this->core->mw->get_subscriptions_by_id( $customer_number );
            if ( !is_wp_error( $subscriptions ) ) {
                foreach ( $subscriptions as $subscription ) {
                    if ( intval($subscription->id->subRef) === intval($sub_ref) ) {
                        if ( !empty($is_full) ) {
                            return $subscription->rate;
                        } else {
                            return $subscription->remainingLiability;
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * This class follows a singleton pattern.
     *
     * @method get_customer_number
     *
     * @return static
     */
    public static function get_instance()
    {
        static $instance = null;

        if ( null === $instance ) {
            $instance = new static();
        }

        return $instance;
    }

}