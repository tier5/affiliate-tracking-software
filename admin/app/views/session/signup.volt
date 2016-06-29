<?php if (!($user_id > 0)) { ?>
<div class="signup-footer">
    <div class="title">Try us for free</div>
    <div class="description">No credit card required.  All features included.</div>
</div>
<div class="content">
    
    <?php } ?>
    {{ content() }}
    <?php
    if ($maxlimitreached) {
    ?>
    The max signup limit has been reached for today.  Please try again tomorrow.
    <p><a href="/session/login" id="register-back-btn" class="btn btn-default" style="margin-right: 50px;">Back</a></p>
    <?php
    } else {
    ?>

    <!-- BEGIN REGISTRATION FORM -->
    <form class="register-form" action="/session/signup/<?=(isset($subscription->subscription_id)?$subscription->subscription_id:'')?><?=(isset($_GET['code'])?'?code='.$_GET['code']:'')?>" method="post" style="display: block;">
        <?php if ($user_id > 0) { ?>
        <h3>Subscribe</h3>
        <p class="hint"> Please enter your credit card information to continue to the Web site. </p>
        <?php } else { ?>
        <?php if (isset($_GET['code'])) {?>
        <h3>You've been invited</h3>
        <?php } else { ?>
        <h3>Account Details</h3>
        <?php } ?>

        <p class="hint"> Enter your account details below: </p>
        <div class="form-group">
            <label class="control-label">Full Name:</label>
            <input class="form-control placeholder-no-fix" type="text" placeholder="Full Name" name="name" value="<?=(isset($_POST['name'])?$_POST["name"]:'')?>" />
        </div>
        <div class="form-group">
            <label class="control-label">Email:</label>
            <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" value="<?=(isset($_POST['email'])?$_POST["email"]:'')?>" />
        </div>
        <div class="form-group">
            <label class="control-label">Password:</label>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="Password" name="password" value="<?=(isset($_POST['password'])?$_POST["password"]:'')?>" />
        </div>
        <div class="form-group">
            <label class="control-label">Re-type Your Password:</label>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="confirmPassword" value="<?=(isset($_POST['confirmPassword'])?$_POST["confirmPassword"]:'')?>" />
        </div><!--
    <div class="form-group">
      <label class="control-label">Business Name:</label>
      <input class="form-control placeholder-no-fix" type="name" autocomplete="off" placeholder="Agency Name" name="agency_name" value="<?=(isset($_POST['agency_name'])?$_POST["agency_name"]:'')?>" />
    </div>-->
        <?php } ?>

        <?php if (isset($subscription->subscription_id)) { ?>
        <p class="hint"> Subscription<?=(isset($subscription->name)?' '.$subscription->name:'')?>: </p>
        <?php
        if (isset($subscription->trial_length) && $subscription->trial_length > 0) {
        ?>
        <p class="hint"><b>Trial Amount <?=(isset($subscription->trial_amount)?'$'.$subscription->trial_amount:'')?> every <?=((isset($subscription->duration)?$subscription->duration * $subscription->trial_length:1) > 1?$subscription->duration:'')?> <?=($subscription->subscription_interval_id == 1?'day':'month').((isset($subscription->duration)?$subscription->duration:1) > 1?'s':'')?></b></p>
        <?php
        }
        ?>
        <p class="hint"><b><?=(isset($subscription->amount)?'$'.$subscription->amount:'')?> every <?=((isset($subscription->duration)?$subscription->duration:1) > 1?$subscription->duration:'')?> <?=($subscription->subscription_interval_id == 1?'day':'month').((isset($subscription->duration)?$subscription->duration:1) > 1?'s':'')?></b></p>
        <div class="card-js">
            <input class="card-number" id="card-number" name="card-number" value="<?=(isset($_POST['card-number'])?$_POST["card-number"]:'')?>" maxlength="19" />
                   <input class="expiry-month" id="expiry-month" name="expiry-month" value="<?=(isset($_POST['expiry-month'])?$_POST["expiry-month"]:'')?>" />
                   <input class="expiry-year" id="expiry-year" name="expiry-year" value="<?=(isset($_POST['expiry-year'])?$_POST["expiry-year"]:'')?>" />
                   <input class="cvc" id="cvc" name="cvc" value="<?=(isset($_POST['cvc'])?$_POST["cvc"]:'')?>" />
        </div>
        <input type="hidden" id="expirationval-m" name="expirationval" value="<?=(isset($_POST['expiry-month'])?$_POST["expiry-month"]:'')?>" />
               <input type="hidden" id="expirationval-y" name="expirationval" value="<?=(isset($_POST['expiry-year'])?$_POST["expiry-year"]:'')?>" />
               <?php } ?>

               <!--<p class="hint"> Viral Sharing & Promotion Code: </p>
               <div class="form-group">
                 <label class="control-label">Share Code</label>
                 <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Share Code" name="sharecode" value="<?=(isset($_POST['sharecode'])?$_POST["sharecode"]:(isset($_GET['code'])?$_GET["code"]:''))?>" />
               </div>-->
               <input type="hidden" name="sharecode" value="<?=(isset($_POST['sharecode'])?$_POST["sharecode"]:(isset($_GET['code'])?$_GET["code"]:''))?>" />
               <!--
               <div class="form-group">
                 <label class="control-label">Address</label>
                 <input class="form-control placeholder-no-fix" type="text" placeholder="Address" name="address" />
               </div>
               <div class="form-group">
                 <label class="control-label">City/Town</label>
                 <input class="form-control placeholder-no-fix" type="text" placeholder="City/Town" name="city" />
               </div>
               <div class="form-group">
                   <label class="control-label">Country</label>
                   <select name="country" class="form-control">
                       <option value="">Country</option>
                       <option value="AF">Afghanistan</option>
                       <option value="AL">Albania</option>
                       <option value="DZ">Algeria</option>
                       <option value="AS">American Samoa</option>
                       <option value="AD">Andorra</option>
                       <option value="AO">Angola</option>
                       <option value="AI">Anguilla</option>
                       <option value="AR">Argentina</option>
                       <option value="AM">Armenia</option>
                       <option value="AW">Aruba</option>
                       <option value="AU">Australia</option>
                       <option value="AT">Austria</option>
                       <option value="AZ">Azerbaijan</option>
                       <option value="BS">Bahamas</option>
                       <option value="BH">Bahrain</option>
                       <option value="BD">Bangladesh</option>
                       <option value="BB">Barbados</option>
                       <option value="BY">Belarus</option>
                       <option value="BE">Belgium</option>
                       <option value="BZ">Belize</option>
                       <option value="BJ">Benin</option>
                       <option value="BM">Bermuda</option>
                       <option value="BT">Bhutan</option>
                       <option value="BO">Bolivia</option>
                       <option value="BA">Bosnia and Herzegowina</option>
                       <option value="BW">Botswana</option>
                       <option value="BV">Bouvet Island</option>
                       <option value="BR">Brazil</option>
                       <option value="IO">British Indian Ocean Territory</option>
                       <option value="BN">Brunei Darussalam</option>
                       <option value="BG">Bulgaria</option>
                       <option value="BF">Burkina Faso</option>
                       <option value="BI">Burundi</option>
                       <option value="KH">Cambodia</option>
                       <option value="CM">Cameroon</option>
                       <option value="CA">Canada</option>
                       <option value="CV">Cape Verde</option>
                       <option value="KY">Cayman Islands</option>
                       <option value="CF">Central African Republic</option>
                       <option value="TD">Chad</option>
                       <option value="CL">Chile</option>
                       <option value="CN">China</option>
                       <option value="CX">Christmas Island</option>
                       <option value="CC">Cocos (Keeling) Islands</option>
                       <option value="CO">Colombia</option>
                       <option value="KM">Comoros</option>
                       <option value="CG">Congo</option>
                       <option value="CD">Congo, the Democratic Republic of the</option>
                       <option value="CK">Cook Islands</option>
                       <option value="CR">Costa Rica</option>
                       <option value="CI">Cote d'Ivoire</option>
                       <option value="HR">Croatia (Hrvatska)</option>
                       <option value="CU">Cuba</option>
                       <option value="CY">Cyprus</option>
                       <option value="CZ">Czech Republic</option>
                       <option value="DK">Denmark</option>
                       <option value="DJ">Djibouti</option>
                       <option value="DM">Dominica</option>
                       <option value="DO">Dominican Republic</option>
                       <option value="EC">Ecuador</option>
                       <option value="EG">Egypt</option>
                       <option value="SV">El Salvador</option>
                       <option value="GQ">Equatorial Guinea</option>
                       <option value="ER">Eritrea</option>
                       <option value="EE">Estonia</option>
                       <option value="ET">Ethiopia</option>
                       <option value="FK">Falkland Islands (Malvinas)</option>
                       <option value="FO">Faroe Islands</option>
                       <option value="FJ">Fiji</option>
                       <option value="FI">Finland</option>
                       <option value="FR">France</option>
                       <option value="GF">French Guiana</option>
                       <option value="PF">French Polynesia</option>
                       <option value="TF">French Southern Territories</option>
                       <option value="GA">Gabon</option>
                       <option value="GM">Gambia</option>
                       <option value="GE">Georgia</option>
                       <option value="DE">Germany</option>
                       <option value="GH">Ghana</option>
                       <option value="GI">Gibraltar</option>
                       <option value="GR">Greece</option>
                       <option value="GL">Greenland</option>
                       <option value="GD">Grenada</option>
                       <option value="GP">Guadeloupe</option>
                       <option value="GU">Guam</option>
                       <option value="GT">Guatemala</option>
                       <option value="GN">Guinea</option>
                       <option value="GW">Guinea-Bissau</option>
                       <option value="GY">Guyana</option>
                       <option value="HT">Haiti</option>
                       <option value="HM">Heard and Mc Donald Islands</option>
                       <option value="VA">Holy See (Vatican City State)</option>
                       <option value="HN">Honduras</option>
                       <option value="HK">Hong Kong</option>
                       <option value="HU">Hungary</option>
                       <option value="IS">Iceland</option>
                       <option value="IN">India</option>
                       <option value="ID">Indonesia</option>
                       <option value="IR">Iran (Islamic Republic of)</option>
                       <option value="IQ">Iraq</option>
                       <option value="IE">Ireland</option>
                       <option value="IL">Israel</option>
                       <option value="IT">Italy</option>
                       <option value="JM">Jamaica</option>
                       <option value="JP">Japan</option>
                       <option value="JO">Jordan</option>
                       <option value="KZ">Kazakhstan</option>
                       <option value="KE">Kenya</option>
                       <option value="KI">Kiribati</option>
                       <option value="KP">Korea, Democratic People's Republic of</option>
                       <option value="KR">Korea, Republic of</option>
                       <option value="KW">Kuwait</option>
                       <option value="KG">Kyrgyzstan</option>
                       <option value="LA">Lao People's Democratic Republic</option>
                       <option value="LV">Latvia</option>
                       <option value="LB">Lebanon</option>
                       <option value="LS">Lesotho</option>
                       <option value="LR">Liberia</option>
                       <option value="LY">Libyan Arab Jamahiriya</option>
                       <option value="LI">Liechtenstein</option>
                       <option value="LT">Lithuania</option>
                       <option value="LU">Luxembourg</option>
                       <option value="MO">Macau</option>
                       <option value="MK">Macedonia, The Former Yugoslav Republic of</option>
                       <option value="MG">Madagascar</option>
                       <option value="MW">Malawi</option>
                       <option value="MY">Malaysia</option>
                       <option value="MV">Maldives</option>
                       <option value="ML">Mali</option>
                       <option value="MT">Malta</option>
                       <option value="MH">Marshall Islands</option>
                       <option value="MQ">Martinique</option>
                       <option value="MR">Mauritania</option>
                       <option value="MU">Mauritius</option>
                       <option value="YT">Mayotte</option>
                       <option value="MX">Mexico</option>
                       <option value="FM">Micronesia, Federated States of</option>
                       <option value="MD">Moldova, Republic of</option>
                       <option value="MC">Monaco</option>
                       <option value="MN">Mongolia</option>
                       <option value="MS">Montserrat</option>
                       <option value="MA">Morocco</option>
                       <option value="MZ">Mozambique</option>
                       <option value="MM">Myanmar</option>
                       <option value="NA">Namibia</option>
                       <option value="NR">Nauru</option>
                       <option value="NP">Nepal</option>
                       <option value="NL">Netherlands</option>
                       <option value="AN">Netherlands Antilles</option>
                       <option value="NC">New Caledonia</option>
                       <option value="NZ">New Zealand</option>
                       <option value="NI">Nicaragua</option>
                       <option value="NE">Niger</option>
                       <option value="NG">Nigeria</option>
                       <option value="NU">Niue</option>
                       <option value="NF">Norfolk Island</option>
                       <option value="MP">Northern Mariana Islands</option>
                       <option value="NO">Norway</option>
                       <option value="OM">Oman</option>
                       <option value="PK">Pakistan</option>
                       <option value="PW">Palau</option>
                       <option value="PA">Panama</option>
                       <option value="PG">Papua New Guinea</option>
                       <option value="PY">Paraguay</option>
                       <option value="PE">Peru</option>
                       <option value="PH">Philippines</option>
                       <option value="PN">Pitcairn</option>
                       <option value="PL">Poland</option>
                       <option value="PT">Portugal</option>
                       <option value="PR">Puerto Rico</option>
                       <option value="QA">Qatar</option>
                       <option value="RE">Reunion</option>
                       <option value="RO">Romania</option>
                       <option value="RU">Russian Federation</option>
                       <option value="RW">Rwanda</option>
                       <option value="KN">Saint Kitts and Nevis</option>
                       <option value="LC">Saint LUCIA</option>
                       <option value="VC">Saint Vincent and the Grenadines</option>
                       <option value="WS">Samoa</option>
                       <option value="SM">San Marino</option>
                       <option value="ST">Sao Tome and Principe</option>
                       <option value="SA">Saudi Arabia</option>
                       <option value="SN">Senegal</option>
                       <option value="SC">Seychelles</option>
                       <option value="SL">Sierra Leone</option>
                       <option value="SG">Singapore</option>
                       <option value="SK">Slovakia (Slovak Republic)</option>
                       <option value="SI">Slovenia</option>
                       <option value="SB">Solomon Islands</option>
                       <option value="SO">Somalia</option>
                       <option value="ZA">South Africa</option>
                       <option value="GS">South Georgia and the South Sandwich Islands</option>
                       <option value="ES">Spain</option>
                       <option value="LK">Sri Lanka</option>
                       <option value="SH">St. Helena</option>
                       <option value="PM">St. Pierre and Miquelon</option>
                       <option value="SD">Sudan</option>
                       <option value="SR">Suriname</option>
                       <option value="SJ">Svalbard and Jan Mayen Islands</option>
                       <option value="SZ">Swaziland</option>
                       <option value="SE">Sweden</option>
                       <option value="CH">Switzerland</option>
                       <option value="SY">Syrian Arab Republic</option>
                       <option value="TW">Taiwan, Province of China</option>
                       <option value="TJ">Tajikistan</option>
                       <option value="TZ">Tanzania, United Republic of</option>
                       <option value="TH">Thailand</option>
                       <option value="TG">Togo</option>
                       <option value="TK">Tokelau</option>
                       <option value="TO">Tonga</option>
                       <option value="TT">Trinidad and Tobago</option>
                       <option value="TN">Tunisia</option>
                       <option value="TR">Turkey</option>
                       <option value="TM">Turkmenistan</option>
                       <option value="TC">Turks and Caicos Islands</option>
                       <option value="TV">Tuvalu</option>
                       <option value="UG">Uganda</option>
                       <option value="UA">Ukraine</option>
                       <option value="AE">United Arab Emirates</option>
                       <option value="GB">United Kingdom</option>
                       <option value="US">United States</option>
                       <option value="UM">United States Minor Outlying Islands</option>
                       <option value="UY">Uruguay</option>
                       <option value="UZ">Uzbekistan</option>
                       <option value="VU">Vanuatu</option>
                       <option value="VE">Venezuela</option>
                       <option value="VN">Viet Nam</option>
                       <option value="VG">Virgin Islands (British)</option>
                       <option value="VI">Virgin Islands (U.S.)</option>
                       <option value="WF">Wallis and Futuna Islands</option>
                       <option value="EH">Western Sahara</option>
                       <option value="YE">Yemen</option>
                       <option value="ZM">Zambia</option>
                       <option value="ZW">Zimbabwe</option>
                   </select>
               </div>-->


               <!--<div class="form-group margin-top-20 margin-bottom-20">
                   <label class="check">
                       <input type="checkbox" name="tnc" /> I agree to the
                       <a href="javascript:;"> Terms of Service </a> &
                       <a href="javascript:;"> Privacy Policy </a>
                   </label>
                   <div id="register_tnc_error"> </div>
               </div>-->
               <div class="form-actions">
            <?php if (isset($haspaid) && $haspaid == false) { ?>
            <br />
            <button type="submit" id="register-submit-btn" class="btn btn-success uppercase pull-right">Submit</button>
            <?php } else { ?>
            <button type="submit" id="register-submit-btn" class="btnsignup uppercase">CREATE MY ACCOUNT</button>

            <div class="signup-footer">By clicking this button, you agree to Review Velocity's
                <a href="#">Anti-span Policy</a> &amp; <a href="#">Terms of Use</a>.</div>
            <?php } ?>
        </div>
        <div style="clear: both;">&nbsp;</div>
    </form>
    <!-- END REGISTRATION FORM -->
    <script type="text/javascript">

    </script>
    <?php
    } // end checking max limit reached
    ?>
    <?php if (!($user_id > 0)) { ?>
</div>
<?php } ?>
