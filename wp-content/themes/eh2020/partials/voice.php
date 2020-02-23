<?php

/**
 * Voice Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$twitter_share_text = "Twitter share text";

?>

<script type="text/x-template" id="voice-template">
    <div id="voice-component" class="voice-form m-0" :class="['step-' + step]">
        <form id="voice-form" class="d-flex align-items-center justify-content-center">
            <div id="" action='' class="px-4 p-md-4 p-lg-5 container" style="max-width: 1200px;">

                <div id="step1" v-show="isStep(1)">
                    <div class="mb-4 ">
                        <h2>Now is the time to act.</h2>
                        <p>Nature has helped ensure a good quality of life and affordable cost of living for us. But now, nature is in crisis and our food security, clean air, water sources and health are at risk.</p>
                        <p>8 in 10 people in Singapore just like you are prepared to do more to fight climate change, but only half know what to do about it. This changes today. </p>
                        <p>When we choose nature, we can protect the future we want.</p>
                        <p>2020 is the year for governments to take action to ensure no more loss of nature. We can put the planet on the path to recovery by 2030. We are closer to a solution than we think.</p>
                        <p>So let’s ask our decision makers to choose nature. By putting nature at the heart of our society and economy, they can help change the fate of human development.</p>
                        <p>Add your voice to write an <strong>open letter to Singapore, from Singapore</strong>. Help the decision makers in your community, workplace and country, make decisions that protect the future you want to see. </p>
                        <p>Start here.</p>
                    </div>
                    <div class="mt-4">
                        <button id="first-button" type="button" class="btn btn-outline-light text-white" @click="nextStep">
                            <span>Build Open letter</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="step2" v-show="isStep(2)">
                    <div class="mb-3 mb-md-5">
                        <h2>Write your future.</h2>
                        <p>To the decision makers in my community, workplace and country, I have an important question for you. </p>
                        <p>In 10 years’ time, will I still be able to: </p>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="form.items.health" value="" id="health">
                                <label class="form-check-label" for="health">
                                    Health &amp; Well-being
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="form.items.economy" value="" id="economy">
                                <label class="form-check-label" for="economy">
                                    Economy &amp; Livelihoods
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="form.items.standardOfLiving" value="" id="standardOfLiving">
                                <label class="form-check-label" for="standardOfLiving">
                                    Standard of Living
                                </label>
                            </div>
                        </div>
                        <p class="error" v-if="errors.issues">Please select atleast one of the above issues.</p>
                    </div>

                    <div class="mt-4">
                        <button type="button" class="btn btn-outline-light text-white" @click="prevStep">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            <span>PREV</span>
                        </button>
                        <button type="button" class="btn btn-outline-light text-white" @click="nextStep">
                            <span>NEXT</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="step3" v-show="isStep(3)">
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="row my-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input :disabled="loading" type="name" class="form-control" v-model="form.name" placeholder="Name">
                                        <p class="error" v-if="errors.name">Please enter a valid name.</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <input id="email" :disabled="loading" type="email" class="form-control" v-model="form.email" placeholder="Email">
                                        <p class="error" v-show="errors.email">Please enter a valid email.</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <input id="phone" :disabled="loading" type="phone" class="form-control" v-model="form.phone" placeholder="Phone">
                                        <p class="error" v-show="errors.phone">Please enter a valid phone.</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <select :disabled="loading" id="country" class="form-control bg-white" v-model="form.country">
                                        <option value="">-- Choose a country --</option>
                                        <option value="AF">Afghanistan</option> 
                                        <option value="AL">Albania</option> 
                                        <option value="DZ">Algeria</option> 
                                        <option value="AS">American Samoa</option> 
                                        <option value="AD">Andorra</option> 
                                        <option value="AO">Angola</option> 
                                        <option value="AI">Anguilla</option> 
                                        <option value="AG">Antigua and Barbuda</option> 
                                        <option value="AR">Argentina</option> 
                                        <option value="AM">Armenia</option> 
                                        <option value="AW">Aruba</option> 
                                        <option value="AU">Australia</option> 
                                        <option value="AT">Austria</option> 
                                        <option value="AZ">Azerbaijan</option> 
                                        <option value="BS">Bahamas</option> 
                                        <option value="BD">Bangladesh</option> 
                                        <option value="BB">Barbados</option> 
                                        <option value="BY">Belarus</option> 
                                        <option value="BE">Belgium</option> 
                                        <option value="BZ">Belize</option> 
                                        <option value="BJ">Benin</option> 
                                        <option value="BM">Bermuda</option> 
                                        <option value="BT">Bhutan</option> 
                                        <option value="BO">Bolivia, Plurinational State of</option> 
                                        <option value="BA">Bosnia and Herzegovina</option> 
                                        <option value="BW">Botswana</option> 
                                        <option value="BR">Brazil</option> 
                                        <option value="IO">British Indian Ocean Territory</option> 
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
                                        <option value="CO">Colombia</option> 
                                        <option value="KM">Comoros</option> 
                                        <option value="CG">Congo</option> 
                                        <option value="CD">Democratic Republic of the Congo</option> 
                                        <option value="CK">Cook Islands</option> 
                                        <option value="CR">Costa Rica</option> 
                                        <option value="CI">Côte d'Ivoire</option> 
                                        <option value="HR">Croatia</option> 
                                        <option value="CU">Cuba</option> 
                                        <option value="CW">Curaçao</option> 
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
                                        <option value="PF">French Polynesia</option> 
                                        <option value="GA">Gabon</option> 
                                        <option value="GM">Gambia</option> 
                                        <option value="GE">Georgia</option> 
                                        <option value="DE">Germany</option> 
                                        <option value="GH">Ghana</option> 
                                        <option value="GI">Gibraltar</option> 
                                        <option value="GR">Greece</option> 
                                        <option value="GL">Greenland</option> 
                                        <option value="GD">Grenada</option> 
                                        <option value="GU">Guam</option> 
                                        <option value="GT">Guatemala</option> 
                                        <option value="GG">Guernsey</option> 
                                        <option value="GN">Guinea</option> 
                                        <option value="GW">Guinea-Bissau</option> 
                                        <option value="HT">Haiti</option> 
                                        <option value="HN">Honduras</option> 
                                        <option value="HK">Hong Kong</option> 
                                        <option value="HU">Hungary</option> 
                                        <option value="IS">Iceland</option> 
                                        <option value="IN">India</option> 
                                        <option value="ID">Indonesia</option> 
                                        <option value="IR">Iran, Islamic Republic of</option> 
                                        <option value="IQ">Iraq</option> 
                                        <option value="IE">Ireland</option> 
                                        <option value="IM">Isle of Man</option> 
                                        <option value="IL">Israel</option> 
                                        <option value="IT">Italy</option> 
                                        <option value="JM">Jamaica</option> 
                                        <option value="JP">Japan</option> 
                                        <option value="JE">Jersey</option> 
                                        <option value="JO">Jordan</option> 
                                        <option value="KZ">Kazakhstan</option> 
                                        <option value="KE">Kenya</option> 
                                        <option value="KI">Kiribati</option> 
                                        <option value="KP">North Korea</option> 
                                        <option value="KR">South Korea</option> 
                                        <option value="KW">Kuwait</option> 
                                        <option value="KG">Kyrgyzstan</option> 
                                        <option value="LA">Lao People's Democratic Republic</option> 
                                        <option value="LV">Latvia</option> 
                                        <option value="LB">Lebanon</option> 
                                        <option value="LS">Lesotho</option> 
                                        <option value="LR">Liberia</option> 
                                        <option value="LY">Libya</option> 
                                        <option value="LI">Liechtenstein</option> 
                                        <option value="LT">Lithuania</option> 
                                        <option value="LU">Luxembourg</option> 
                                        <option value="MO">Macao</option> 
                                        <option value="MK">Republic of Macedonia</option> 
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
                                        <option value="MX">Mexico</option> 
                                        <option value="FM">Micronesia, Federated States of</option> 
                                        <option value="MD">Republic of Moldova</option> 
                                        <option value="MC">Monaco</option> 
                                        <option value="MN">Mongolia</option> 
                                        <option value="ME">Montenegro</option> 
                                        <option value="MS">Montserrat</option> 
                                        <option value="MA">Morocco</option> 
                                        <option value="MZ">Mozambique</option> 
                                        <option value="MM">Myanmar</option> 
                                        <option value="NA">Namibia</option> 
                                        <option value="NR">Nauru</option> 
                                        <option value="NP">Nepal</option> 
                                        <option value="NL">Netherlands</option> 
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
                                        <option value="PS">Palestinian Territory</option> 
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
                                        <option value="RO">Romania</option> 
                                        <option value="RU">Russian</option> 
                                        <option value="RW">Rwanda</option> 
                                        <option value="KN">Saint Kitts and Nevis</option> 
                                        <option value="WS">Samoa</option> 
                                        <option value="SM">San Marino</option> 
                                        <option value="ST">Sao Tome and Principe</option> 
                                        <option value="SA">Saudi Arabia</option> 
                                        <option value="SN">Senegal</option> 
                                        <option value="RS">Serbia</option> 
                                        <option value="SC">Seychelles</option> 
                                        <option value="SL">Sierra Leone</option>
                                        <option value="SG">Singapore</option> 
                                        <option value="SX">Sint Maarten</option> 
                                        <option value="SK">Slovakia</option> 
                                        <option value="SI">Slovenia</option> 
                                        <option value="SB">Solomon Islands</option> 
                                        <option value="SO">Somalia</option> 
                                        <option value="ZA">South Africa</option> 
                                        <option value="SS">South Sudan</option> 
                                        <option value="ES">Spain</option> 
                                        <option value="LK">Sri Lanka</option> 
                                        <option value="SD">Sudan</option> 
                                        <option value="SR">Suriname</option> 
                                        <option value="SZ">Swaziland</option> 
                                        <option value="SE">Sweden</option> 
                                        <option value="CH">Switzerland</option> 
                                        <option value="SY">Syria</option> 
                                        <option value="TW">Taiwan, Province of China</option> 
                                        <option value="TJ">Tajikistan</option> 
                                        <option value="TZ">Tanzania</option> 
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
                                        <option value="UY">Uruguay</option> 
                                        <option value="UZ">Uzbekistan</option> 
                                        <option value="VU">Vanuatu</option> 
                                        <option value="VE">Venezuela, Bolivarian Republic of</option> 
                                        <option value="VN">Viet Nam</option> 
                                        <option value="VI">Virgin Islands</option> 
                                        <option value="YE">Yemen</option> 
                                        <option value="ZM">Zambia</option> 
                                        <option value="ZW">Zimbabwe</option></select>
                                    </select>
                                    <p class="error" v-if="errors.country">Please select a country.</p>
                                </div>
                            </div>
                            <div :disabled="loading"  class="text-left custom-control custom-checkbox mx-2">
                                <div class="mb-2">
                                    <input :disabled="loading" class="custom-control-input" type="checkbox" value="Yes" v-model="form.check_age" id="check_age">
                                    <label class="custom-control-label pt-1" for="check_age">
                                        I am above 21 years old 
                                    </label>
                                </div>
                                <div>
                                    <input :disabled="loading" class="custom-control-input" type="checkbox" value="Yes" v-model="form.check_pdpc" id="check_pdpc">
                                    <label class="custom-control-label pt-1" for="check_pdpc">
                                        PDPC checkbox
                                    </label>
                                </div>
                                <p class="error mt-3 mb-0" v-show="errors.check_pdpc">Please check the age checkbox</p>
                                <p class="error mb-0" v-show="errors.check_age">Please accept the PDPC terms</p>
                            </div>
                            <div class="text-md-left my-4">
                                <button :disabled="loading" type="button" class="btn-block btn btn-g btn-outline-light text-white" @click="nextStep">
                                    <span>MAKE MY VOICE COUNT</span>
                                    <svg v-show="!loading" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                    <svg width="40" height="24" class="loading" v-show="loading" version="1.1" id="L4" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                        viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                                        <circle fill="#000000" stroke="none" cx="0" cy="50" r="8">
                                            <animate
                                            attributeName="opacity"
                                            dur="1s"
                                            values="0;1;0"
                                            repeatCount="indefinite"
                                            begin="0.1"/>    
                                        </circle>
                                        <circle fill="#000000" stroke="none" cx="33" cy="50" r="8">
                                            <animate
                                            attributeName="opacity"
                                            dur="1s"
                                            values="0;1;0"
                                            repeatCount="indefinite" 
                                            begin="0.2"/>       
                                        </circle>
                                        <circle fill="#000000" stroke="none" cx="66" cy="50" r="8">
                                            <animate
                                            attributeName="opacity"
                                            dur="1s"
                                            values="0;1;0"
                                            repeatCount="indefinite" 
                                            begin="0.3"/>     
                                        </circle>
                                    </svg>
                                </button>
                                <a class="text-center d-block mt-3 text-white" @click="prevStep">
                                    <span>Go back to previous step</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-8">
                            <br>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat accusantium at, sequi incidunt sunt dicta, facilis officia quibusdam debitis tempore quisquam eum enim itaque ad accusamus commodi omnis voluptate repellendus.</p>
                        </div>
                    </div>
                </div>

                <div id="step4" v-show="step == 4">
                    <div>
                        <h5>Thank you for adding your voice to this open letter to Singapore, from Singapore.</h5>
                        <p>Your voice will be used in 2020 to help Singapore’s decision-makers in our community, workplace and country tackle our planetary emergency.</p>
                        <div class="mt-4">
                            <h5 class="sub-heading pb-2 pb-md-4">Spread the word</h5>
                            <a class="btn btn-outline-dark text-white btn-share-fb border-0" :href="'https://www.facebook.com/sharer/sharer.php?u=' + shareUrl" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#fff" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg> &nbsp; SHARE
                            </a>
                            <a class="btn btn-outline-dark text-white btn-share-tw border-0" :href="'https://twitter.com/intent/tweet?text=<?= $twitter_share_text ?>&amp;hashtags=PlasticDiet&amp;url=' + shareUrl" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#fff" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg> &nbsp; TWEET
                            </a>
                        </div>
                        <p>Want to do more? Check your inbox for a special digital emergency kit and take action today!</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</script>

<div id="contente">
    <plastictest></plastictest>
</div>

<script>
    var nonce = '<?= wp_create_nonce('voice_form'); ?>';
    var step = 1;
    var utm_campaign = '<?php echo (isset($_GET['utm_campaign']) && !empty($_GET['utm_campaign'])) ? $_GET['utm_campaign'] : '' ?>';
    var utm_source = '<?php echo (isset($_GET['utm_source']) && !empty($_GET['utm_source'])) ? $_GET['utm_source'] : '' ?>';
    var utm_medium = '<?php echo (isset($_GET['utm_medium']) && !empty($_GET['utm_medium'])) ? $_GET['utm_medium'] : '' ?>';
    var utm_content = '<?php echo (isset($_GET['utm_content']) && !empty($_GET['utm_content'])) ? $_GET['utm_content'] : '' ?>';
    var utm_term = '<?php echo (isset($_GET['utm_term']) && !empty($_GET['utm_term'])) ? $_GET['utm_term'] : '' ?>';
</script>