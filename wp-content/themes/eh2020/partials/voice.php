<?php

/**
 * Voice Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$title = "title";
$description = "description";
$share_text = "Twitter share text";

$age = [];
$age[] = 'Below 18';
$age[] = '18-24';
$age[] = '25-35';
$age[] = '36-50';
$age[] = '51-69';
$age[] = '70 and above';

?>

<script type="text/x-template" id="voice-template">
    <div id="voice-component" class="voice-form m-0" :class="['step-' + step]">
        <form id="voice-form" class="d-flex align-items-center justify-content-center">
            <div id="" action='' class="px-4 p-md-4 p-lg-5 w-100">

                <div id="step0" class="container" v-show="isStep(0)">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="mb-4 ">
                                <h2 class="mb-">Open Letter to Singapore</h2>
                                <p>Nature and the food security, clean air, water sources and good health it provides us are at risk.</p>
                                <p>2020 is the year for governments to take action to make sure our future here in Singapore and on this planet is secure.</p>
                                <p>Add your voice to an open letter that we will use to push Singapore’s decision makers to make tough decisions that protect your future.</p>
                            </div>
                            <div class="mt-4">
                                <button id="first-button" type="button" class="btn btn-gradient text-white" @click="nextStep">
                                    <span>Write Your Future Now</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="col-6">
                            <img class="w-100" src="<?php echo get_stylesheet_directory_uri() ?>/imgs/signature.png" alt="">
                        </div>
                    </div>
                </div>

                <div id="step1" class="container" v-show="isStep(1)">
                    <div class="row align-items-center">
                        <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-0">
                            <div class="mb-3 mb-md-3">
                                <p><strong>Step 1</strong></p>
                                <h3>Write your future.</h3>
                                <p>Let’s kick this off. How are you feeling about your future?</p>
                                <div>
                                    <input type="button" class="btn btn-outline-gradient text-white mr-2" :class="{active: selected}" v-for="(selected, name) in form.feelings" :value="name" @click="updateFeeling(name)">
                                    <div class="btn btn-outline-gradient text-white mr-2" @click="addFeelingInput">+</div>
                                </div>
                                <p class="error" v-if="errors.feelings">{{ errors.feelings }}</p>
                            </div>

                            <div class="mt-5">
                                <button type="button" class="btn btn-block btn-outline-gradient text-white" @click="nextStep">
                                    <span>NEXT</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </button>

                                <button type="button" class="btn btn-block btn-outline-gradient text-white" @click="prevStep">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left">
                                        <polyline points="15 18 9 12 15 6"></polyline>
                                    </svg>
                                    <span>PREV</span>
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-6 d-none d-lg-block">
                            <div class="openletter w-100 h-100 bg-white text-dark">
                                <h3 class="h6 mb-3">AN OPEN LETTER TO SINGAPORE</h3>
                                <p>Dear Singapore,</p>
                                <p>It’s been a rough start to 2020. Forest fires, health emergencies and more.</p>
                                <p>I’m feeling <span v-for="(selected, name) in form.feelings">{{selected ? `${name}, ` : ''}}</span>.</p>
                                <!-- TODO:
                                    1. If user only picks one adjective, and when they click next, place a "." at the end.
                                    2. If user picks 2 adjectives, place an "and" in between of the 2 adjectives for e.g. "I'm feeling anxious and hopeful."
                                    3. If user picks more than 3 adjectives, and they click next, place "and" before the last adjective, for e.g. "I'm feeling anxious, hopeful and demonic."
                                 -->
                            </div>
                        </div>
                    </div>
                </div>

                <div id="step2" class="container" v-show="isStep(2)">
                    <div class="row align-items-center">
                        <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-0">
                            <div class="mb-3 mb-md-3">
                                <p>Step 2</p>
                                <h2>Write your future.</h2>
                                <p>What do you hope for in 2030?</p>
                                <div>
                                    <div ref="issues" class="form-check">
                                        <input class="form-check-input" type="checkbox" v-model="form.issues.health" value="" id="health">
                                        <label class="form-check-label" for="health">
                                            I want to continue enjoying the quality of life I’m accustomed to
                                        </label>
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" v-model="form.issues.economy" value="" id="economy">
                                                <label class="form-check-label" for="economy">
                                                    With amazing natural green spaces for everyone to enjoy
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" v-model="form.issues.economy" value="" id="economy">
                                                <label class="form-check-label" for="economy">
                                                    With the food I love remaining readily available and affordable
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" v-model="form.issues.economy" value="" id="economy">
                                                <label class="form-check-label" for="economy">
                                                    Is there anything else you're worried about?
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" v-model="form.issues.economy" value="" id="economy">
                                        <label class="form-check-label" for="economy">
                                            I want to maintain the good health I’ve enjoyed
                                        </label>
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" v-model="form.issues.economy" value="" id="economy">
                                                <label class="form-check-label" for="economy">
                                                    With the air I breathe being free from haze
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" v-model="form.issues.economy" value="" id="economy">
                                                <label class="form-check-label" for="economy">
                                                    With the food I eat being free of microplastics
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" v-model="form.issues.economy" value="" id="economy">
                                                <label class="form-check-label" for="economy">
                                                    Is there anything else you're worried about?
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" v-model="form.issues.standardOfLiving" value="" id="standardOfLiving">
                                        <label class="form-check-label" for="standardOfLiving">
                                            I want to see a bright and prosperous future for Singapore
                                        </label>
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" v-model="form.issues.economy" value="" id="economy">
                                                <label class="form-check-label" for="economy">
                                                    With my home safe from sea level rise and climate change
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" v-model="form.issues.economy" value="" id="economy">
                                                <label class="form-check-label" for="economy">
                                                    With confidence for my family’s future
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" v-model="form.issues.economy" value="" id="economy">
                                                <label class="form-check-label" for="economy">
                                                    With continued economic growth for Singapore
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" v-model="form.issues.economy" value="" id="economy">
                                                <label class="form-check-label" for="economy">
                                                    Is there anything else you're worried about?
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <textarea class="mt-2 w-100 p-2" style="outline: 0" v-model="form.issues.custom_message" maxlength="120" cols="30" rows="3" placeholder="Add your own message"></textarea>
                                <small>{{ 120 - form.issues.custom_message.length }} characters left.</small>
                                <p class="error" v-if="errors.issues">{{ errors.issues }}</p>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <button type="button" class="btn btn-block btn-outline-gradient text-white" @click="prevStep">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left">
                                            <polyline points="15 18 9 12 15 6"></polyline>
                                        </svg>
                                        <span>PREV</span>
                                    </button>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-block btn-outline-gradient text-white" @click="nextStep">
                                        <span>NEXT</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 d-none d-lg-block">
                            <div class="openletter w-100 h-100 bg-white text-dark">
                                <h3 class="h6 mb-3">AN OPEN LETTER TO SINGAPORE</h3>
                                <h3 class="h6 mb-3">AN OPEN LETTER TO SINGAPORE</h3>
                                <p>Dear Singapore,</p>
                                <p>It’s been a rough start to 2020. Forest fires, health emergencies and more.</p>
                                <p>I’m feeling <span v-for="(selected, name) in form.feelings">{{selected ? `${name}, ` : ''}}</span>.</p>
                                <p>I’m not used to worrying so much, and lately I’ve started to wonder if we are taking everything we have here in Singapore for granted.</p>
                                <p>The world in 2020 seems like a pretty frightening place. Our demands on the planet are coming back to us with more natural disasters exacerbated by climate change and an unprecedented loss of nature around the world.</p>
                                <p>So what will 2030 look like? Will I still be able to:</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="step3" class="container" v-show="isStep(3)">
                    <div class="row  align-items-center">
                        <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-0">
                            <div class="row my-3">
                                <div class="col-7">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input ref="first_name" id="first_name" :disabled="loading" type="name" class="form-control" v-model="form.first_name" placeholder="Your first name">
                                        <p class="error my-2" v-if="errors.first_name">Please enter a valid first name.</p>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input ref="last_name" id="last_name" :disabled="loading" type="name" class="form-control" v-model="form.last_name" placeholder="Your last name">
                                        <p class="error my-2" v-if="errors.last_name">Please enter a valid last name.</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="email">Your Email</label>
                                        <input ref="email" id="email" :disabled="loading" type="email" class="form-control" v-model="form.email" placeholder="Your email">
                                        <p class="error my-2" v-show="errors.email">Please enter a valid email.</p>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="form-group">
                                        <label for="phone">Your Mobile Number</label>
                                        <VuePhoneNumberInput ref="phone" id="phone" :no-flags=true :translations="{countrySelectorLabel: 'Code pays', countrySelectorError: 'Choisir un pays', phoneNumberLabel: 'Numéro de téléphone', example: 'Example : '}" :disabled="loading" v-model="form.phone" :default-country-code="form.country" @update="onUpdatePhone" :key="form.country" color="#000000" valid-color="#000000" error-color="#000000" />
                                        <!-- <input :disabled="loading" type="number" class="form-control" v-model="form.phone" placeholder="Your mobile number"> -->
                                        <p class="error my-2" v-show="errors.phone">Please enter a valid phone.</p>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label for="age">Your Age</label>
                                        <select ref="age" :disabled="loading" class="form-control bg-white" v-model="form.age"  id="age">
                                            <option value="">- Age -</option>
                                            <?php foreach ($age as $a) { ?><option value="<?php echo $a ?>"><?php echo $a ?></option><?php } ?>
                                        </select>
                                        <p class="error my-2" v-show="errors.age">{{ errors.age }}</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="country">Your country</label>
                                    <select ref="country" :disabled="loading" id="country" class="form-control bg-white" v-model="form.country">
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
                                    <p class="error my-2" v-if="errors.country">Please select a country.</p>
                                </div>
                            </div>
                            <div :disabled="loading"  class="text-left custom-control custom-checkbox mx-2">
                                <div>
                                    <input ref="check_pdpc" :disabled="loading" class="custom-control-input" type="checkbox" value="Yes" v-model="form.check_pdpc" id="check_pdpc">
                                    <label class="custom-control-label pt-1" for="check_pdpc">
                                        By submitting this form, you agree to WWF's <a href="https://www.wwf.sg/wwf_singapore/pdp_policy/" target="_blank">Privacy Policy</a> and Terms and Conditions. WWF will use the information you give to be in touch with you and to provide updates on our areas of work and actions you can take. By accepting, you acknowledge and consent to WWF sending you such updates.
                                    </label>
                                </div>
                                <div class="mt-2">
                                    <p class="error mb-0" v-show="errors.check_pdpc">Please accept privacy policy and terms and conditions</p>
                                </div>
                            </div>
                            <p class="error mt-2 text-center" v-show="errors.random">{{ errors.random }}</p>
                            <div class="text-md-left mt-3">
                                <button :disabled="loading" type="button" class="btn-block btn btn-lg btn-gradient text-white" @click="nextStep">
                                    <span>MAKE MY VOICE COUNT</span>
                                    <svg class="mb-1 feather feather-chevron-right" v-show="!loading" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                    <svg width="40" height="24" class="loading" v-show="loading" version="1.1" id="L4" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                        viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                                        <circle fill="#ffffff" stroke="none" cx="0" cy="50" r="8">
                                            <animate
                                            attributeName="opacity"
                                            dur="1s"
                                            values="0;1;0"
                                            repeatCount="indefinite"
                                            begin="0.1"/>    
                                        </circle>
                                        <circle fill="#ffffff" stroke="none" cx="33" cy="50" r="8">
                                            <animate
                                            attributeName="opacity"
                                            dur="1s"
                                            values="0;1;0"
                                            repeatCount="indefinite" 
                                            begin="0.2"/>       
                                        </circle>
                                        <circle fill="#ffffff" stroke="none" cx="66" cy="50" r="8">
                                            <animate
                                            attributeName="opacity"
                                            dur="1s"
                                            values="0;1;0"
                                            repeatCount="indefinite" 
                                            begin="0.3"/>     
                                        </circle>
                                    </svg>
                                </button>
                                <a :disabled="loading" class="text-center d-block mt-3 text-white" @click="prevStep">
                                    <span>Go back to previous step</span>
                                </a>
                            </div>
                            
                        </div>

                        <div class="col-lg-6 d-none d-lg-block">
                            <div class="openletter w-100 h-100 bg-white text-dark">
                                <h3 class="h6 mb-3">AN OPEN LETTER TO SINGAPORE</h3>
                                <p>Dear Singapore,</p>
                                <p>How are you holding up? What a start to 2020! Fear and uncertainty have gripped the world with relentless forest fires, devastating health emergencies and more.</p>
                                <p>I’m anxious. You probably are too. </p>
                                <p>I’m not used to worrying so much in Singapore, where we enjoy a good standard of living and a sense of well-being. But lately I’ve started to wonder if we are taking it all for granted.</p>
                                <p>The world in 2020 seems like a pretty frightening place. Our demands on the planet are coming back to us. In my lifetime, I’ve seen more natural disasters caused by climate change and an unprecedented loss of wildlife around the world. </p>
                                <p>So what will 2030 look like? Will I still be able to:</p>
                                <ul>
                                    <li v-if="form.issues.standardOfLiving"><strong>Enjoy the quality of life we are accustomed to here in Singapore? </strong><br>With amazing natural green spaces and readily available, affordable seafood</li>
                                    <li v-if="form.issues.health"><strong>Maintain the good health I’ve enjoyed?</strong><br>With breathable air and food free of microplastics</li>
                                    <li v-if="form.issues.economy"><strong>See a bright and prosperous future for Singapore?</strong><br>With a future-proofed energy strategy and readiness for the new reality that our climate changed world will bring</li>
                                    <li v-if="form.issues.custom_message">{{ form.issues.custom_message }}</li>
                                </ul>
                                <p>These fears are not something that future generations will have to deal with. I feel it. Today, I am writing to ask you to help ensure the well-being of Singapore’s people, our families and the economy. 2020 is a year of important decisions that will set the path for the next decade.</p>
                                <p>I’m doing everything I can. But I cannot face this alone. In writing this letter I invite Singapore’s decision makers - our politicians, our community leaders, our businesses, our lawmakers - to help me understand how we can reach a 2030 free from the uncertainty and anxiety that I feel for it right now. For me, for my family, for my future children and beyond.</p>
                                <p>Sincerely,</p>
                                <p>[YOUR NAME]</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="step4" v-show="step == 4">
                    <div class="container">
                        <!-- <h3 class="h6 mb-3">AN OPEN LETTER TO SINGAPORE</h3> -->
                        <h5>Thank you for adding your voice to this open letter to Singapore, from Singapore.</h5>
                        <p>Your voice will be used in 2020 to help Singapore’s decision-makers in our community, workplace and country tackle our planetary emergency.</p>
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
                        <img  v-show="!image_loading" class="share-image" :src="shareImage" alt="">
                        <div class="my-4">
                            <h5 class="sub-heading pb-2 pb-md-4">Spread the word</h5>
                            <a style="background-color: rgb(71, 89, 147);" class="btn-social mb-2 btn btn-outline-dark text-white btn-share-fb border-0" :href="'https://www.facebook.com/sharer/sharer.php?u=' + shareUrl" target="_blank">
                                <svg class="mr-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 408.788 408.788" style="enabl-background:new 0 0 408.788 408.788;" xml:space="preserve">
                                    <path style="fill:#fff;" d="M353.701,0H55.087C24.665,0,0.002,24.662,0.002,55.085v298.616c0,30.423,24.662,55.085,55.085,55.085 h147.275l0.251-146.078h-37.951c-4.932,0-8.935-3.988-8.954-8.92l-0.182-47.087c-0.019-4.959,3.996-8.989,8.955-8.989h37.882 v-45.498c0-52.8,32.247-81.55,79.348-81.55h38.65c4.945,0,8.955,4.009,8.955,8.955v39.704c0,4.944-4.007,8.952-8.95,8.955 l-23.719,0.011c-25.615,0-30.575,12.172-30.575,30.035v39.389h56.285c5.363,0,9.524,4.683,8.892,10.009l-5.581,47.087 c-0.534,4.506-4.355,7.901-8.892,7.901h-50.453l-0.251,146.078h87.631c30.422,0,55.084-24.662,55.084-55.084V55.085 C408.786,24.662,384.124,0,353.701,0z"/>
                                </svg>  SHARE
                            </a>
                            <a style="background-color: #76A9EA;" class="btn-social mb-2 btn btn-outline-dark text-white btn-share-tw border-0" :href="'https://twitter.com/intent/tweet?text=<?= $share_text ?>&hashtags=PlasticDiet&url=' + shareUrl" target="_blank">
                                <svg class="mr-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 410.155 410.155" style="enabl-background:new 0 0 410.155 410.155;" xml:space="preserve">
                                    <path style="fill:#fff;" d="M403.632,74.18c-9.113,4.041-18.573,7.229-28.28,9.537c10.696-10.164,18.738-22.877,23.275-37.067
                                        l0,0c1.295-4.051-3.105-7.554-6.763-5.385l0,0c-13.504,8.01-28.05,14.019-43.235,17.862c-0.881,0.223-1.79,0.336-2.702,0.336
                                        c-2.766,0-5.455-1.027-7.57-2.891c-16.156-14.239-36.935-22.081-58.508-22.081c-9.335,0-18.76,1.455-28.014,4.325
                                        c-28.672,8.893-50.795,32.544-57.736,61.724c-2.604,10.945-3.309,21.9-2.097,32.56c0.139,1.225-0.44,2.08-0.797,2.481
                                        c-0.627,0.703-1.516,1.106-2.439,1.106c-0.103,0-0.209-0.005-0.314-0.015c-62.762-5.831-119.358-36.068-159.363-85.14l0,0
                                        c-2.04-2.503-5.952-2.196-7.578,0.593l0,0C13.677,65.565,9.537,80.937,9.537,96.579c0,23.972,9.631,46.563,26.36,63.032
                                        c-7.035-1.668-13.844-4.295-20.169-7.808l0,0c-3.06-1.7-6.825,0.485-6.868,3.985l0,0c-0.438,35.612,20.412,67.3,51.646,81.569
                                        c-0.629,0.015-1.258,0.022-1.888,0.022c-4.951,0-9.964-0.478-14.898-1.421l0,0c-3.446-0.658-6.341,2.611-5.271,5.952l0,0
                                        c10.138,31.651,37.39,54.981,70.002,60.278c-27.066,18.169-58.585,27.753-91.39,27.753l-10.227-0.006
                                        c-3.151,0-5.816,2.054-6.619,5.106c-0.791,3.006,0.666,6.177,3.353,7.74c36.966,21.513,79.131,32.883,121.955,32.883
                                        c37.485,0,72.549-7.439,104.219-22.109c29.033-13.449,54.689-32.674,76.255-57.141c20.09-22.792,35.8-49.103,46.692-78.201
                                        c10.383-27.737,15.871-57.333,15.871-85.589v-1.346c-0.001-4.537,2.051-8.806,5.631-11.712c13.585-11.03,25.415-24.014,35.16-38.591
                                        l0,0C411.924,77.126,407.866,72.302,403.632,74.18L403.632,74.18z"/>
                                </svg> TWEET
                            </a>
                            <a style="background-color: #7AD06D;" class="btn-social mb-2 btn btn-outline-dark text-white btn-share-tw border-0" :href="'https://api.whatsapp.com/send?text=<?= $share_text ?>&hashtags=PlasticDiet&url=' + shareUrl" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 418.135 418.135" style="enabl-background:new 0 0 418.135 418.135;" xml:space="preserve">
                                    <g>
                                        <path style="fill:#fff;" d="M198.929,0.242C88.5,5.5,1.356,97.466,1.691,208.02c0.102,33.672,8.231,65.454,22.571,93.536 L2.245,408.429c-1.191,5.781,4.023,10.843,9.766,9.483l104.723-24.811c26.905,13.402,57.125,21.143,89.108,21.631 c112.869,1.724,206.982-87.897,210.5-200.724C420.113,93.065,320.295-5.538,198.929,0.242z M323.886,322.197 c-30.669,30.669-71.446,47.559-114.818,47.559c-25.396,0-49.71-5.698-72.269-16.935l-14.584-7.265l-64.206,15.212l13.515-65.607 l-7.185-14.07c-11.711-22.935-17.649-47.736-17.649-73.713c0-43.373,16.89-84.149,47.559-114.819 c30.395-30.395,71.837-47.56,114.822-47.56C252.443,45,293.218,61.89,323.887,92.558c30.669,30.669,47.559,71.445,47.56,114.817 C371.446,250.361,354.281,291.803,323.886,322.197z"/>
                                        <path style="fill:#fff;" d="M309.712,252.351l-40.169-11.534c-5.281-1.516-10.968-0.018-14.816,3.903l-9.823,10.008 c-4.142,4.22-10.427,5.576-15.909,3.358c-19.002-7.69-58.974-43.23-69.182-61.007c-2.945-5.128-2.458-11.539,1.158-16.218 l8.576-11.095c3.36-4.347,4.069-10.185,1.847-15.21l-16.9-38.223c-4.048-9.155-15.747-11.82-23.39-5.356 c-11.211,9.482-24.513,23.891-26.13,39.854c-2.851,28.144,9.219,63.622,54.862,106.222c52.73,49.215,94.956,55.717,122.449,49.057 c15.594-3.777,28.056-18.919,35.921-31.317C323.568,266.34,319.334,255.114,309.712,252.351z"/>
                                    </g>
                                </svg>
                                <!-- &nbsp; WHATSAPP -->
                            </a>
                            <a style="background-color: #039be5;" class="btn-social mb-2 btn btn-outline-dark text-white btn-share-tw border-0" :href="'tg://msg_url?text=<?= $share_text ?>&url=' + shareUrl" target="_blank">
                                <svg viewBox="5 5 14 14" xmlns="http://www.w3.org/2000/svg">
                                    <!-- <circle cx="12" cy="12" fill="#" r="12"/> -->
                                    <path d="m5.491 11.74 11.57-4.461c.537-.194 1.006.131.832.943l.001-.001-1.97 9.281c-.146.658-.537.818-1.084.508l-3-2.211-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.121l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953z" fill="#fff"/>
                                </svg> 
                                <!-- &nbsp; TELEGRAM -->
                            </a>
                            <a style="background-color: #0077B7" class="btn-social mb-2 btn btn-outline-dark text-white btn-share-tw border-0" :href="'https://www.linkedin.com/shareArticle?mini=true&title=<?= $title ?>&summary=<?= $description ?>&url=' + shareUrl" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 382 382" style="enabl-background:new 0 0 382 382;" xml:space="preserve">
                                    <path style="fill:#fff;" d="M347.445,0H34.555C15.471,0,0,15.471,0,34.555v312.889C0,366.529,15.471,382,34.555,382h312.889 C366.529,382,382,366.529,382,347.444V34.555C382,15.471,366.529,0,347.445,0z M118.207,329.844c0,5.554-4.502,10.056-10.056,10.056 H65.345c-5.554,0-10.056-4.502-10.056-10.056V150.403c0-5.554,4.502-10.056,10.056-10.056h42.806 c5.554,0,10.056,4.502,10.056,10.056V329.844z M86.748,123.432c-22.459,0-40.666-18.207-40.666-40.666S64.289,42.1,86.748,42.1 s40.666,18.207,40.666,40.666S109.208,123.432,86.748,123.432z M341.91,330.654c0,5.106-4.14,9.246-9.246,9.246H286.73 c-5.106,0-9.246-4.14-9.246-9.246v-84.168c0-12.556,3.683-55.021-32.813-55.021c-28.309,0-34.051,29.066-35.204,42.11v97.079 c0,5.106-4.139,9.246-9.246,9.246h-44.426c-5.106,0-9.246-4.14-9.246-9.246V149.593c0-5.106,4.14-9.246,9.246-9.246h44.426 c5.106,0,9.246,4.14,9.246,9.246v15.655c10.497-15.753,26.097-27.912,59.312-27.912c73.552,0,73.131,68.716,73.131,106.472 L341.91,330.654L341.91,330.654z"/>
                                </svg>
                                <!-- &nbsp; LINKEDIN -->
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