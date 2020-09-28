<?php
?>
<div id="tfs_css_body" class="tfs_csd_container">
    <div id="tfs_css_content" style="display: block;">
        <h2>Extreme Fortunes</h2>
        <p>
            There are numerous investments primed to spin off huge windfalls that could put millions in your pocket. With
            Extreme Fortunes, we aim to bring you the best of those each month.
        </p>
        <hr>
        <table>
            <tbody>
                <tr>
                    <td>
                        Status:
                    </td>
                    <td>
                        Active
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td>
                        Renewal Date:
                    </td>
                    <td>
                        10/03/2017
                    </td>
                    <td>
                        <button class="csd_ext_renewal_date_change">Change</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        Automatic Renewal:
                    </td>
                    <td>
                        On
                    </td>
                    <td>
                        <button class="csd_ext_auto_renewal_change">Change</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        Renewal Price:
                    </td>
                    <td>
                        <span class="csd_ext_strike">$54</span> ... $45
                    </td>
                    <td>
                        <button class="csd_ext_renewal_price_change">Change</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        Email:
                    </td>
                    <td>
                        sample@email.com
                    </td>
                    <td>
                        <button class="csd_ext_email_change" data-subref="test"
                                data-subs-email="sample@email.com">
                            Change
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>
                        SMS Alerts:
                    </td>
                    <td>
                        0123456789
                    </td>
                    <td>
                        <input type="hidden" name="csd_ext_old_text_alert"
                               value="<?php echo !empty( $phone ) ? $phone : ''; ?>">
                        <button id="csd_ext_text_alert_change">Change</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Popup Modal -->
        <div style="display: none">
            <div id="csd_ext_modal">
            </div>
        </div>
    </div>
</div>