<?php
/**
 * Copyright (C) Threefold systems - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */

class CSS_User_Response_Object
{
    /**
     * @var
     */
    private $old_email;

    /**
     * @var
     */
    private $new_email;

    /**
     * @var
     */
    private $username = false;

    /**
     * @var
     */
    private $subscriptions = false;

    /**
     * @var
     */
    private $eletters = false;

    /**
     * @var
     */
    private $actual_url;

    /**
     * @return mixed
     */
    public function get_old_email()
    {
        return $this->old_email;
    }

    /**
     * @param string $old_email
     */
    public function set_old_email($old_email)
    {
        $this->old_email = $old_email;
    }

    /**
     * @return mixed
     */
    public function get_new_email()
    {
        return $this->new_email;
    }

    /**
     * @param mixed $new_email
     */
    public function set_new_email($new_email)
    {
        $this->new_email = $new_email;
    }

    /**
     * @return mixed
     */
    public function get_username()
    {
        return $this->username;
    }

    /**
     * @param boolean $username
     */
    public function set_username($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function get_eletters()
    {
        return $this->eletters;
    }

    /**
     * @param boolean $eletters
     */
    public function set_eletters($eletters)
    {
        $this->eletters = $eletters;
    }

    /**
    * @return mixed
     */
    public function get_subscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @param boolean $subscriptions
     */
    public function set_subscriptions($subscriptions)
    {
        $this->subscriptions = $subscriptions;
    }

    /**
     * @return mixed
     */
    public function get_actual_url()
    {
        return $this->actual_url;
    }

    /**
     * @param string $actual_url
     */
    public function set_actual_url($actual_url)
    {
        $this->actual_url = $actual_url;
    }
}