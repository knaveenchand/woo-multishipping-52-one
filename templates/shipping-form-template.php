<form method="post" class="mspp_addresses_form" id="mspp_addresses_form" style="display: block;">
	<div id="addresses" class="address-column">
<p class="form-row form-row-first validate-required">
id="shipping_first_name_field" data-priority="10">
        <label for="shipping_first_name" class="">First name&nbsp;<abbr class="required"
                title="required">*</abbr></label>
        <span
        class="woocommerce-input-wrapper">
                <input type="text" class="input-text "
                name="shipping_first_name"
                id="shipping_first_name"
                placeholder="" value="" autocomplete="given-name">
                </span>
</p>
<p class="form-row form-row-last validate-required">
id="shipping_last_name_field" data-priority="20">
        <label for="shipping_last_name" class="">Last name&nbsp;<abbr class="required"
                title="required">*</abbr></label>
        <span
        class="woocommerce-input-wrapper">
                <input type="text" class="input-text "
                name="shipping_last_name"
                id="shipping_last_name" placeholder=""
                value="" autocomplete="family-name">
                </span>
</p>
<p class="form-row form-row-wide address-field update_totals_on_change validate-required"
id="shipping_country_field" data-priority="40">
        <label for="shipping_country" class="">Country / Region&nbsp;<abbr class="required"
                title="required">*</abbr></label>
        <span
        class="woocommerce-input-wrapper"><strong>United States (US)</strong>
                <input
                type="hidden" name="shipping_country"
                id="shipping_country" value="US"
                autocomplete="country" class="country_to_state"
                readonly="readonly">
                        </span>
</p>
<p class="form-row form-row-wide address-field validate-required"
id="shipping_address_1_field" data-priority="50">
        <label for="shipping_address_1" class="">Street address&nbsp;<abbr class="required"
                title="required">*</abbr></label>
        <span
        class="woocommerce-input-wrapper">
                <input type="text" class="input-text "
                name="shipping_address_1"
                id="shipping_address_1" placeholder="House number and street name"
                value="" autocomplete="address-line1">
                </span>
</p>
<p class="form-row form-row-wide address-field validate-required"
id="shipping_city_field" data-priority="70">
        <label for="shipping_city" class="">Town / City&nbsp;<abbr class="required"
                title="required">*</abbr></label>
        <span
        class="woocommerce-input-wrapper">
                <input type="text" class="input-text "
                name="shipping_city" id="shipping_city"
                placeholder="" value="" autocomplete="address-level2">
                </span>
</p>
<p class="form-row form-row-wide address-field validate-required validate-state"
id="shipping_state_field" data-priority="80">
        <label for="shipping_state" class="">State&nbsp;<abbr class="required"
                title="required">*</abbr></label>
        <span
        class="woocommerce-input-wrapper">
                <select name="shipping_state" id="shipping_state"
                class="state_select " autocomplete="address-level1"
                data-placeholder="Select an option…"
                data-input-classes="" data-label="State">
                    <option value="">Select an option…</option>
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="DC">District Of Columbia</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA">Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                    <option value="AA">Armed Forces (AA)</option>
                    <option value="AE">Armed Forces (AE)</option>
                    <option value="AP">Armed Forces (AP)</option>
                </select>
                </span>
</p>
<p class="form-row form-row-wide address-field validate-required validate-postcode"
id="shipping_postcode_field" data-priority="90">
        <label for="shipping_postcode" class="">ZIP&nbsp;<abbr class="required" title="required">*</abbr></label>
        <span
        class="woocommerce-input-wrapper">
                <input type="text" class="input-text "
                name="shipping_postcode"
                id="shipping_postcode" placeholder=""
                value="" autocomplete="postal-code">
                </span>
</p>
	</div>
	<div class="form-row">
		<input type="hidden" name="idx" value="-1">
		<input type="hidden" name="shipping_account_address_action" value="save">
		<input type="submit" name="set_addresses" id="mspp_set_address_btn" value="Save Address" class="button alt"> </div>
</form>