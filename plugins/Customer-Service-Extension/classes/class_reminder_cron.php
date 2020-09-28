<?php
/**
 * Copyright (C) Threefold systems - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */
namespace csd_ext;

include_once('class_middleware.php');
require(__DIR__ . '/../config.php');
/**
 * Class: class_reminder_cron
 * Description:
 * Version:
 * @author: Threefold Systems
 */
class class_reminder_cron
{
    /**
     *  Constructor Method.
     *
     * @method __construct
     */
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->middleware = class_middleware::get_instance();
        $this->mail_id = AUTO_RENEW_REMINDER_ID;
    }

    /**
     * Determine if the user should be sent the email
     *
     * @method csd_ext_reminder_email
     *
     */
    public function csd_ext_reminder_email( )
    {
        $users = $this->get_users_for_reminder();

        if ( !empty($users) ) {
            foreach ($users as $user) {
                $subscriptions = $this->middleware->core->mw->get_subscriptions_by_id($user->customerNumber);
                if (!is_wp_error($subscriptions)) {
                    foreach ($subscriptions as $subscription) {
                        if ($subscription->id->subRef === $user->subRef && ($subscription->renewMethod === 'C')) {
                            $this->csd_ext_send_reminder_email($user);
                            break;
                        }
                    }
                }
            }
        }
    }


    /**
     * function to generate and send reminder emails
     *
     * @method csd_ext_send_reminder_email
     *
     * @param object $user object from db containing user details for reminder email
     *
     */
    public function csd_ext_send_reminder_email($user, $mail_id)
    {
        $email = $this->middleware->core->mw->findSubscriptionEmailAddressBySubRef($user->subRef);

        if (!is_wp_error($email)) {

            $mail_content = $this->middleware->core->get_language_variable('inp_csd_ext_auto_renew_email',
                array('title' => $user->subName));

            $send_auto_renew_email = $this->middleware->core->mc->put_trigger_mailing(
                $this->mail_id,
                $email[0]->emailAddress,
                array('email_body' => $mail_content)
            );

            if (!empty($send_auto_renew_email) && !is_wp_error($send_auto_renew_email)) {
                $table_name = $this->wpdb->prefix . 'csd_ext_auto_renewals';
                $this->wpdb->update($table_name,
                    array('reminderSent' => 'true'),
                    array('subRef' => $user->subRef, 'customerNumber' => $user->customerNumber)
                );
            }
        }
    }

    /**
     * function to process enable auto renew request
     *
     * @method get_users_for_reminder
     *
     * @return array $users All valid users from db
     */
    public function get_users_for_reminder()
    {
        $table_name = $this->wpdb->prefix . 'csd_ext_auto_renewals';
        $two_days = strtotime('+2 days');
        $sql = "SELECT * FROM $table_name WHERE reminderSent = 'false' AND expireDate <= $two_days;";
        $users = $this->wpdb->get_results($sql);
        return $users;
    }
}
$class_reminder_cron = new class_reminder_cron;