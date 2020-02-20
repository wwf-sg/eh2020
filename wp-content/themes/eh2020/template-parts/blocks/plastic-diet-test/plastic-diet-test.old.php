<?php

/**
 * plastic-diet-test Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'plastic-diet-test-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}
$id = 'testeo';

$age = [];
$age[] = 'Below 18';
$age[] = '18-24';
$age[] = '25-35';
$age[] = '36-50';
$age[] = '51-69';
$age[] = '70 and above';

// Create class attribute allowing for custom "className" and "align" values.
$className = 'plastic-diet-test';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// variables
$country = get_user_country_info();
$image = get_field('image') ?: 295;
$signature_count = _getSignatureCount($country->countryCode);
$p_id = get_the_ID();


$grams = get_field('grams') ?: 'grams';
if ($p_id == 1043) {
    $grams = 'gramos';
}

$placeholders_o = [
    '{{ plastic_test_global }}' => "{{ signatureCount.plastic_test.global }}",
    '{{ signature_count_global }}' => "{{ signatureCount.global }}",
    '{{ signature_count_local }}' => "{{ signatureCount.local }}",
    '{{ user_name }}' => "{{ form.name }}",
    '{{ plastic_value }}'   => '{{ form.plastic_value }}',
    '{{ plastic_name }}' => '{{ form.plastic_name.name }}',
];

$placeholders = [
    '{{ plastic_test_global }}' => "<strong class='plastic_test_count global'>{{ signatureCount.plastic_test.global }}</strong>",
    '{{ signature_count_global }}' => "<strong class='grams signature_count global'>{{ signatureCount.global }}</strong>",
    '{{ signature_count_local }}' => "<strong class='signature_count global'>{{ signatureCount.local }}</strong>",
    '{{ user_name }}' => "<span class='user blue impact highlight'>{{ form.name }}</span>",
    '{{ plastic_value }}'   => '<span class="plastic_value highlight red impact">&nbsp;{{ form.plastic_value }} ' . $grams . '&nbsp;</span>',
    '{{ plastic_name }}' => '<span class="plastic_name">{{ form.plastic_name.name }}</span>',
];

$placeholder_o = function ($variab) use ($placeholders) {
    foreach ($placeholders as $key => $value) {
        $variab = str_replace($key, $value, $variab);
    }
    return $variab;
};
$placeholder = function ($variab) use ($placeholders) {
    foreach ($placeholders as $key => $value) {
        $variab = str_replace($key, $value, $variab);
    }
    return $variab;
};

// Load values and assing defaults.
// Step 0
$button = get_field('translate_button');
$pen_cap = get_field('translate_pen_cap');
$bottle_cap = get_field('translate_bottle_cap');
$spoon = get_field('translate_spoon');
$credit_card = get_field('translate_credit_card');
$next = get_field('translate_next');
$prev = get_field('translate_prev');
$short_form_check_line_1 = get_field('short_form_check_line_1');
$short_form_check_line_2 = get_field('short_form_check_line_2');
$short_form_check_line_3 = get_field('short_form_check_line_3');
$short_form_check_line_4 = get_field('short_form_check_line_4');
$short_form_check_line_5 = get_field('short_form_check_line_5');
$short_form_check_line_6 = get_field('short_form_check_line_6');
$short_form_check_line_button = get_field('short_form_check_line_button');
$short_form_check_line_skip = get_field('short_form_check_line_skip');

// Step 1
$find_out_how_much_plastic_you_eat_now = $placeholder(get_field('find_out_how_much_plastic_you_eat_now'));
$people_globally_have_taken_the_test = $placeholder(get_field('people_globally_have_taken_the_test'));
$enter_your_name_to_start = get_field('form_fields_enter_your_name_to_start');
$your_full_name = get_field('form_fields_your_full_name');
$full_name_validation_text = get_field('form_fields_full_name_validation_text');

// Step 2
$hello = $placeholder(get_field('hello'));
$where_are_you_from = $placeholder(get_field('where_are_you_from'));
$country_label = get_field('form_fields_country_label');
$country_validation_text = get_field('form_fields_country_validation_text');

// step 3
$step_3_line_1 = $placeholder(get_field('step_3_line_1'));
$items_validation_text = get_field('form_fields_items_validation_text');
$water = get_field('water');
$shellfish = get_field('shellfish');
$beer = get_field('beer');
$salt = get_field('salt');
$never = get_field('never');
$occasionally = get_field('occasionally');
$frequently = get_field('frequently');
$water_tooltip = get_field('water_tooltip');
$shellfish_tooltip = get_field('shellfish_tooltip');
$beer_tooltip = get_field('beer_tooltip');
$salt_tooltip = get_field('salt_tooltip');

// step 4
$step_4_line_1 = addslashes($placeholder(get_field('step_4_line_1')));
$breathe_tooltip = get_field('breath_tooltip');
$yes = get_field('yes');
$no = get_field('no');
$warning = get_field('popup_warning');
$warning_message = $placeholder(get_field('popup_warning_message'));
$right_i_do_breathe = get_field('popup_right_i_do_breathe');

// step 5
$plastic_text = get_field('plastic_text') ?: "Hey, {{ user_name }} you're consuming approximately {{ plastic_value }} of plastics a week!";
$plastic_text_2 = get_field('plastic_text_2');
$plastic_text_3 = get_field('plastic_text_3');
$thats_crazy_right = get_field('thats_crazy_right');
$lets_fix_it = get_field('lets_fix_it');
$lets_fix_it_url = get_field('lets_fix_it_url');
$open_in_a_new_tab = get_field('open_in_a_new_tab');

// step 6
$step_6_content = $placeholder(get_field('step_6_content'));
$you_can_help = get_field('you_can_help') ?: "YOU CAN HELP";

// step 7
$header_text = $placeholder(get_field('step_7_content_1'));
$step_7_content_2 = $placeholder(get_field('step_7_content_2'));
$fields_control = get_field('step_7_form_fields');
$make_my_voice_count = get_field('step_7_form_fields_make_my_voice_count');
$skip = get_field('skip');

// step 8 | last step
$path_2_message = get_field('path_2_message');
$message_on_top = $placeholder(get_field('message_on_top') ?: "");
$share_message = $placeholder(get_field('share_message') ?: '');
$facebook_share_text = addslashes(get_field('facebook_share_text') ?: "I can't believe how much plastic we're eating!");
$twitter_share_text = addslashes(get_field('twitter_share_text') ?: "I can't believe how much plastic we're eating!");

if ($p_id == 1043) {
    $grams = 'gramos';
}


if (!is_admin()) { ?>
    <script type="text/x-template" id="plastictest-template">
    <?php } ?>
                <div id="what-can-i-do" class="plastic-diet-form m-0 <?php echo esc_attr($className); ?>" :class="[{'has-iframe': iframe},'step-' + step, 'path-' + path, { 'modl': (step >= 3 && step <= 7)}]">
                    <form id="take-the-plastic-test" class="text-center d-flex align-items-center justify-content-center" :key="someVariable">
                        <div id="" action='' class="p-2 p-md-4 p-lg-5 container" style="max-width: 1200px;">
                            
                            <div id="step1" class="" v-show="isStep(1)">
                                <div class="mb-4">
                                    <label for="user_name" class="h2 mb-4">
                                        <?= $find_out_how_much_plastic_you_eat_now ?>
                                    </label>
                                    <div>
                                        <p><?= $people_globally_have_taken_the_test ?></p>
                                    </div>
                                </div>
                                <div class="position-relative d-none d-md-block">
                                    <?php if (!empty($enter_your_name_to_start)) { ?>
                                        <div class="position-absolute" style="top: -50px; left: 80px;">
                                            <p style="color: #2085ff; font-weight: bolder; transform: rotate(-20deg); max-width: 120px;"><?= $enter_your_name_to_start ?></p>
                                            <img style="max-width: 100px; height: auto; position: relative; right: -70px; transform: rotate(-20deg); top: -30px;" src="<?= get_template_directory_uri() ?>/imgs/arrow.png" alt="arrow">
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="mb-5">
                                    <input id="user_name" class="border-0 bg-transparent p-0 m-0 user-name form-control d-block text-center text-uppercase" type="text" v-model="form.name" v-on:keyup.enter="nextStep" placeholder="<?= $your_full_name ?>">
                                    <p class="error" v-if="errors.name"><?= $full_name_validation_text ?></p>
                                </div>
                                <div class="mt-4">
                                    <button id="first-button" type="button" class="btn btn-g btn-outline-dark" @click="nextStep">
                                        <span><?= $next ?></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div id="step2" v-show="isStep(2)">
                                <div class="mb-3 mb-md-5">
                                    <h4 class="h2 mb-2 mb-md-4"><?= $hello ?></h4>
                                    <label for="country" class="h2 mb-2 mb-md-4">
                                        <?= $where_are_you_from ?>
                                    </label>
                                </div>
                                <div class="mb-5">
                                    <select id="country" class="form-control" v-model="form.country">
                                        <option value="">-- <?php echo $country_label ?> --</option>
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
                                    <p class="error" v-if="errors.country"><?= $country_validation_text ?></p>
                                </div>
                                <div class="mt-4" data-toggle="buttons">
                                    <button type="button" class="btn btn-g btn-outline-dark" @click="prevStep">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left">
                                            <polyline points="15 18 9 12 15 6"></polyline>
                                        </svg>
                                        <span><?= $prev ?></span>
                                    </button>
                                    <button type="button" class="btn btn-g btn-outline-dark" @click="nextStep">
                                        <span><?= $next ?></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div id="step3" v-show="isStep(3)">
                                <h3 class="mb-3 mb-md-5 mx-md-5"><?= $step_3_line_1 ?></h3>
                                <div class="p-2">
                                    <div class="mb-3 mb-md-5 row">
                                        <div class="col-12 col-md-4">
                                            <h5 class="item-title"><span class="red impact highlight">&nbsp;<?= $water ?>&nbsp;</span> <div class="tt" data-tippy-content="<?= $water_tooltip ?>"><img src="<?= get_stylesheet_directory_uri() ?>/imgs/info-icon.png" alt="tooltip info"></div></h5>
                                        </div>
                                        <div class="col-12 col-md-8 btn-group btn-group-toggle" data-toggle="butto">
                                            <!-- <label class="btn btn-secondary" :class="{ active: (form.items.water == 'never')}">
                                                <input value="never" type="radio" v-model="form.items.water" id="saltoption1" autocomplete="off" > <?= $never ?>
                                            </label> -->
                                            <label class="btn btn-secondary" :class="{ active: (form.items.water == 'occasionally')}">
                                                <input value="occasionally" type="radio" v-model="form.items.water" id="saltoption2" autocomplete="off" checked> <?= $occasionally ?>
                                            </label>
                                            <label class="btn btn-secondary" :class="{ active: (form.items.water == 'frequently')}">
                                                <input value="frequently" type="radio" v-model="form.items.water" id="saltoption3" autocomplete="off"> <?= $frequently ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3 mb-md-5 row">
                                        <div class="col-12 col-md-4">
                                            <h5 class="item-title"><span class="red impact highlight">&nbsp;<?= $shellfish ?>&nbsp;</span> <div class="tt" data-tippy-content="<?= $shellfish_tooltip ?>"><img src="<?= get_stylesheet_directory_uri() ?>/imgs/info-icon.png" alt="tooltip info"></div></h5>
                                        </div>
                                        <div class="col-12 col-md-8 btn-group btn-group-toggle" data-toggle="butto">
                                            <label class="btn btn-secondary" :class="{ active: (form.items.shellfish == 'never')}">
                                                <input value="never" type="radio" v-model="form.items.shellfish" id="shellfishoption1" autocomplete="off" > <?= $never ?>
                                            </label>
                                            <label class="btn btn-secondary" :class="{ active: (form.items.shellfish == 'occasionally')}">
                                                <input value="occasionally" type="radio" v-model="form.items.shellfish" id="shellfishoption2" autocomplete="off" checked> <?= $occasionally ?>
                                            </label>
                                            <label class="btn btn-secondary" :class="{ active: (form.items.shellfish == 'frequently')}">
                                                <input value="frequently" type="radio" v-model="form.items.shellfish" id="shellfishoption3" autocomplete="off"> <?= $frequently ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3 mb-md-5 row">
                                        <div class="col-12 col-md-4">
                                            <h5 class="item-title"><span class="red impact highlight">&nbsp;<?= $beer ?>&nbsp;</span> <div class="tt" data-tippy-content="<?= $beer_tooltip ?>"><img src="<?= get_stylesheet_directory_uri() ?>/imgs/info-icon.png" alt="tooltip info"></div></h5>
                                        </div>
                                        <div class="col-12 col-md-8 btn-group btn-group-toggle" data-toggle="butto">
                                        <label class="btn btn-secondary" :class="{ active: (form.items.beer == 'never')}">
                                            <input value="never" type="radio" v-model="form.items.beer" id="beeroption1" autocomplete="off" > <?= $never ?>
                                        </label>
                                        <label class="btn btn-secondary" :class="{ active: (form.items.beer == 'occasionally')}">
                                            <input value="occasionally" type="radio" v-model="form.items.beer" id="beeroption2" autocomplete="off" checked> <?= $occasionally ?>
                                        </label>
                                        <label class="btn btn-secondary" :class="{ active: (form.items.beer == 'frequently')}">
                                            <input value="frequently" type="radio" v-model="form.items.beer" id="beeroption3" autocomplete="off"> <?= $frequently ?>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="mb-3 mb-md-5 row">
                                        <div class="col-12 col-md-4">
                                            <h5 class="item-title"><span class="red impact highlight">&nbsp;<?= $salt ?>&nbsp;</span> <div class="tt" data-tippy-content="<?= $salt_tooltip ?>"><img src="<?= get_stylesheet_directory_uri() ?>/imgs/info-icon.png" alt="tooltip info"></div></h5>
                                        </div>
                                        <div class="col-12 col-md-8 btn-group btn-group-toggle" data-toggle="butto">
                                            <label class="btn btn-secondary" :class="{ active: (form.items.salt == 'never')}">
                                                <input value="never" type="radio" v-model="form.items.salt" id="saltoption1" autocomplete="off" > <?= $never ?>
                                            </label>
                                            <label class="btn btn-secondary" :class="{ active: (form.items.salt == 'occasionally')}">
                                                <input value="occasionally" type="radio" v-model="form.items.salt" id="saltoption2" autocomplete="off" checked> <?= $occasionally ?>
                                            </label>
                                            <label class="btn btn-secondary" :class="{ active: (form.items.salt == 'frequently')}">
                                                <input value="frequently" type="radio" v-model="form.items.salt" id="saltoption3" autocomplete="off"> <?= $frequently ?>
                                            </label>
                                        </div>
                                    </div>
                                    <p class="error" v-if="errors.items"><?= $items_validation_text ?></p>
                                </div>
                                <div class="mt" data-toggle="buttons">
                                    <button type="button" class="btn btn-g btn-outline-dark" @click="prevStep">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left">
                                            <polyline points="15 18 9 12 15 6"></polyline>
                                        </svg>
                                        <span><?= $prev ?></span>
                                    </button>
                                    <button type="button" class="btn btn-g btn-outline-dark" @click="nextStep">
                                        <span><?= $next ?></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div id="step4" v-show="isStep(4)">
                                <h3 class="mb-5">
                                    <?= $step_4_line_1 ?> <div class="tt" data-tippy-content="<?= $breathe_tooltip ?>"><img src="<?= get_stylesheet_directory_uri() ?>/imgs/info-icon.png" alt="tooltip info"></div>
                                </h3>
                                <div class="m-5" data-toggle="buttons">
                                    <button :disabled="loading" type="button" class="btn btn-lg btn-outline-dark mr-5" data-toggle="modal" data-target="#exampleModalLong"><?= $no ?></button>
                                    <button :disabled="loading" type="button" class="btn btn-lg btn-outline-dark" @click="nextStep"><?= $yes ?></button>
                                </div>

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
                                <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content bg-primary text-white border-primary">
                                            <div class="modal-body p-md-5">
                                                <h4><?= $warning ?></h4>
                                                <p class="mx-4 my-3 mb-md-5">
                                                    <?= $warning_message ?>
                                                </p>
                                                <div>
                                                    <button type="button" class="bg-transparent btn btn-light text-white" @click="nextStep" data-dismiss="modal">
                                                        <span><?= $right_i_do_breathe ?></span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                                            <polyline points="9 18 15 12 9 6"></polyline>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <div id="step5" class="container" v-show="isStep(5)">
                                <div class="short-form mt-5" v-show="short_form_check">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div v-show="path == 2">
                                                <h4 class="mb-2">You're consuming approximately <span class="plastic_value highlight red impact">&nbsp;5 grams &nbsp;</span> of plastic a week.</h4>
                                                <img style="min-width: 100px" :src="'<?= get_stylesheet_directory_uri() ?>/imgs/credit-card.png'" class="plastic-image img-responsive img-fluid m-md-4">
                                                <h3 class="mb-4">That's like a credit card!</h3>
                                            </div>
                                            <div v-show="path != 2">
                                                <h4 class="mb-2"><?= $placeholder($short_form_check_line_1) ?></h4>
                                                <img style="min-width: 100px" :src="'<?= get_stylesheet_directory_uri() ?>/imgs/' + form.plastic_name.image + '.png'" class="plastic-image img-responsive img-fluid m-md-4">
                                                <h3 class="mb-4"><?= $placeholder($short_form_check_line_2) ?></h3>
                                            </div>
                                        </div>
                                        <div class="mb-4 text-center w-100 col-md-1"><svg class="d-md-none" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 7.33l2.829-2.83 9.175 9.339 9.167-9.339 2.829 2.83-11.996 12.17z"></path></svg></div>
                                        <div class="col-md-5">
                                            <h3 class="mb-3"><?= $placeholder($short_form_check_line_3) ?></h3>
                                            <hr class="seperator">
                                            <h6  v-show="path == 2" class="mb-4">Help us convince governments to sign a global legally binding agreement to end plastic pollution.</h6>
                                            <h6  v-show="path != 2" class="mb-4"><?= $placeholder($short_form_check_line_4) ?></h6>
                                            <div class="mb-3">
                                                <div class="text-left" style=" font-weight: bold; font-size: 110%; ">
                                                    <?= $placeholder($short_form_check_line_5) ?>
                                                </div>
                                                <div style="background-color: #b8d4f4;">
                                                    <div style="background-color: #2287ff;height: 10px;max-width: 80%;"></div>
                                                </div>
                                                <div class="text-right" style="font-weight: bold;"><?= $placeholder($short_form_check_line_6) ?></div>
                                            </div>
                                            <div class="mx-md-4" style="max-width">
                                                <div class="row mt-2 mt-md-0 ">
                                                    <div class="col-12" v-show="path == 2">
                                                        <div class="form-group">
                                                            <input :disabled="loading" type="name" class="form-control" v-model="form.name" placeholder="<?php echo $your_full_name ?>">
                                                            <p class="error" v-if="errors.name"><?= $full_name_validation_text ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-12" v-show="useEmailField">
                                                        <div class="form-group">
                                                            <input id="email" :disabled="loading" type="email" class="form-control" v-model="form.email" placeholder="<?php echo $fields_control['email_field']['label'] ?>">
                                                            <p class="error" v-show="errors.email"><?php echo $fields_control['email_field']['validation_text'] ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-12" v-show="path == 2">
                                                        <div class="form-group">
                                                            <select :disabled="loading" id="country" class="form-control" v-model="form.country">
                                                                <option value="">-- <?php echo $country_label ?> --</option>
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
                                                            <p class="error" v-if="errors.country"><?= $country_validation_text ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-12" v-show="usePhoneField">
                                                        <div class="form-group">
                                                            <VuePhoneNumberInput color="#ffeb00" :translations="translations" :disabled="loading" v-model="form.phone" :default-country-code="form.country" @update="onUpdatePhone" :key="form.country" />
                                                            <p class="error phone" v-show="errors.phone"><?php echo $fields_control['phone_field']['validation_text'] ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-12" v-show="useAgeField">
                                                        <div class="form-group">
                                                            <select :disabled="loading" class="form-control" v-model="form.age"  id="age">
                                                                <?php // if(strtolower($country->countryCode) == 'sg') : ?>
                                                                <option value="">- Age -</option>
                                                                <?php foreach ($age as $a) { ?><option value="<?php echo $a ?>"><?php echo $a ?></option><?php } ?>
                                                            </select>
                                                            <!-- <v-select :disabled="loading" v-model="form.age" :options="<?= json_encode($age) ?>" :clearable="false"></v-select> -->
                                                            <p class="error age" v-show="errors.age"><?php echo $fields_control['age_field']['validation_text'] ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-12" v-show="useMessageField">
                                                        <div class="form-group">
                                                            <input type="text" :disabled="loading" class="form-control" v-model="form.message" placeholder="<?php echo $fields_control['message_field']['label'] ?>">
                                                            <p class="error" v-show="errors.message"><?php echo $fields_control['message_field']['validation_text'] ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div :disabled="loading"  class="text-left custom-control custom-checkbox mx-2"  v-show="useMarketingField">
                                                    <input :disabled="loading" class="custom-control-input" type="checkbox" value="Yes" v-model="form.marketing" id="marketing_message">
                                                    <label class="custom-control-label" for="marketing_message">
                                                        <?php echo $fields_control['marketing_field']['label'] ?>
                                                    </label>
                                                    <p class="error marketing" v-show="errors.marketing"><?php echo $fields_control['marketing_field']['validation_text'] ?></p>
                                                    
                                                </div>
                                                <div class="text-md-left mt-4">
                                                    <button :disabled="loading" type="button" class="btn btn-block btn-g btn-outline-dark" @click="nextStep">
                                                        <span><?= $short_form_check_line_button ?></span>
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
                                                    <br>
                                                </div>
                                                <div class="mb-4" v-show="path == 1">
                                                    <a  @click="skip" class="mt-md-4 d-inline-block text-lowercase" style="font-size: 85%;"><?= $short_form_check_line_skip ?></a>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                <div v-show="!short_form_check">
                                    <div class="row align-items-center mx-0 mb-3 mb-md-5">
                                        <div class="col-md-7 text-left" style="max-width: ">   
                                            <h4><?= $placeholder($plastic_text) ?></h4>
                                            <hr class="seperator">
                                            <h4 class="pt-3 d-inline" v-if="form.plastic_value < 6"><?= $placeholder($plastic_text_2) ?></h4>
                                            <h4 class="pt-3 d-inline" v-if="form.plastic_value >= 6"><?= $placeholder($plastic_text_3) ?></h4>
                                        </div>
                                        <div class="col-md-5">
                                            <img style="min-width: 100px" :src="'<?= get_stylesheet_directory_uri() ?>/imgs/' + form.plastic_name.image + '.png'" class="plastic-image img-responsive img-fluid">
                                        </div>
                                    </div>
                                    <h4><?= $thats_crazy_right ?></h4>
                                    <div class="mt-4">
                                        <button type="button" class="btn btn-g btn-outline-dark" @click="nextStep">
                                            <span><?= $lets_fix_it ?></span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                                <polyline points="9 18 15 12 9 6"></polyline>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div id="step6" v-show="isStep(6)">
                                <div class="container py-md-5 text-left text-md-center" style="max-width: 500px;">
                                    <div class="align-items-center">
                                        <?= $step_6_content ?>
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-g btn-outline-dark" @click="nextStep">
                                            <span><?= $you_can_help ?></span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                                <polyline points="9 18 15 12 9 6"></polyline>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div id="step7" v-show="isStep(7)">
                                <div class="container align-items-center py-4" v-show="cta_check">
                                    <div class="bg-transparent blue text-left text-md-center p-0 mb-md-5" style="font-size: 120%;">
                                        <p><?php echo $header_text ?></p>
                                    </div>
                                    <div class="row" style="max-width: 960px;">
                                        <div class="col-12 col-md-5">
                                            <div class="text-left">
                                                <?= $step_7_content_2 ?>
                                            </div>
                                        </div>
                                        <div class="d-none d-md-block col-md-1"></div>
                                        <div class="col-12 col-md-6">
                                            <div class="mx-md-4" style="max-width">
                                                <div class="row mt-2 mt-md-0 ">
                                                    <div class="col-12" v-show="path == 2">
                                                        <div class="form-group">
                                                            <input :disabled="loading" type="name" class="form-control" v-model="form.name" placeholder="<?php echo $your_full_name ?>">
                                                            <p class="error" v-if="errors.name"><?= $full_name_validation_text ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-12" v-show="useEmailField">
                                                        <div class="form-group">
                                                            <input id="email" :disabled="loading" type="email" class="form-control" v-model="form.email" placeholder="<?php echo $fields_control['email_field']['label'] ?>">
                                                            <p class="error" v-show="errors.email"><?php echo $fields_control['email_field']['validation_text'] ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-12" v-show="path == 2">
                                                        <div class="form-group">
                                                            <select :disabled="loading" id="country" class="form-control" v-model="form.country">
                                                                <option value="">-- <?php echo $country_label ?> --</option>
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
                                                            <p class="error" v-if="errors.country"><?= $country_validation_text ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-8"  :class="{'col-12': !useAgeField}" v-show="usePhoneField">
                                                        <div class="form-group">
                                                            <VuePhoneNumberInput color="#ffeb00" :translations="translations" :disabled="loading" v-model="form.phone" :default-country-code="form.country" @update="onUpdatePhone" :key="form.country" />
                                                            <p class="error phone" v-show="errors.phone"><?php echo $fields_control['phone_field']['validation_text'] ?></p>
                                                            <p class="error age" v-show="errors.age"><?php echo $fields_control['age_field']['validation_text'] ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-4" :class="{'col-12': !usePhoneField}" v-show="useAgeField">
                                                        <div class="form-group">
                                                            <select :disabled="loading" class="form-control" v-model="form.age"  id="age">
                                                                <option value="">- Age -</option>
                                                                <?php foreach ($age as $a) { ?><option value="<?php echo $a ?>"><?php echo $a ?></option><?php } ?>
                                                            </select>
                                                            <!-- <v-select :disabled="loading" v-model="form.age" :options="<?= json_encode($age) ?>" :clearable="false"></v-select> -->
                                                            <p class="error age" :class="{'d-none': usePhoneField}" v-show="errors.age"><?php echo $fields_control['age_field']['validation_text'] ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-12" v-show="useMessageField">
                                                        <div class="form-group">
                                                            <input type="text" :disabled="loading" class="form-control" v-model="form.message" placeholder="<?php echo $fields_control['message_field']['label'] ?>">
                                                            <p class="error" v-show="errors.message"><?php echo $fields_control['message_field']['validation_text'] ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div :disabled="loading"  class="text-left custom-control custom-checkbox mx-2"  v-show="useMarketingField">
                                                    <input :disabled="loading" class="custom-control-input" type="checkbox" value="Yes" v-model="form.marketing" id="marketing_message">
                                                    <label class="custom-control-label" for="marketing_message">
                                                        <?php echo $fields_control['marketing_field']['label'] ?>
                                                    </label>
                                                    <p class="error marketing" v-show="errors.marketing"><?php echo $fields_control['marketing_field']['validation_text'] ?></p>
                                                    
                                                </div>
                                                <div class="text-md-left my-4">
                                                    <button :disabled="loading" type="button" class="btn btn-g btn-outline-dark" @click="nextStep">
                                                        <span><?= $make_my_voice_count ?></span>
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
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-show="path == 1">
                                        <a  @click="skip" class="mt-md-4 d-inline-block text-lowercase" style="font-size: 85%;"><?php echo $skip ?></a>
                                    </div>
                                </div>
                            </div>

                            <div id="step8" v-show="step == 8">
                                <div>
                                    <div v-show="form.country == 'SG'" >
                                        <div v-show="short_form_check" class="text-left container" style="max-width: 480px;">
                                            <h5 class="mb-3">Thanks for adding your voice, {{ form.name }}!</h5>
                                            <h6>We will use your voice to ask for change at <span class="blue bg-transparent">every relevant</span> meeting of <span class="blue bg-transparent">governments and businesses</span> that we have access to, anywhere in the world - and you’ll hear about exactly how they go.</h6>
                                            <ul>
                                                <li>G20, Osaka, June</li>
                                                <li>G7, Biarritz, August</li>
                                                <li>Pacific Leaders Forum, August</li>
                                                <li>UN General Assembly, New York, September</li>
                                                <li>Coral Triangle Senior Officials Meeting, November</li>
                                            </ul>
                                        </div>
                                        <div v-show="!short_form_check">
                                            <h5 class="mb-5"><?= $message_on_top ?></h5>
                                        </div>
                                    </div>
                                    <div v-show="form.country != 'SG'">
                                        <h5 class="mb-5"><?= $message_on_top ?></h5>
                                    </div>
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
                                </div>
                                <div class="mt-4">
                                    <h5 class="sub-heading pb-2 pb-md-4"><?= $share_message ?></h5>
                                    <a class="btn btn-outline-dark text-white btn-share-fb border-0" :href="'https://www.facebook.com/sharer/sharer.php?u=' + shareUrl" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#fff" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg> &nbsp; SHARE
                                    </a>
                                    <a class="btn btn-outline-dark text-white btn-share-tw border-0" :href="'https://twitter.com/intent/tweet?text=<?= $twitter_share_text ?>&amp;hashtags=PlasticDiet&amp;url=' + shareUrl" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#fff" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg> &nbsp; TWEET
                                    </a>
                                </div>

                                <div class="mt-4" v-show="!cta_check">
                                    <a class="btn btn-g btn-outline-dark" href="<?= $lets_fix_it_url ?>" <?php echo ($open_in_a_new_tab) ? " target='_blank' rel='noopener'" : ''; ?> @click="newMethod('<?= $lets_fix_it_url ?>')">
                                        <span><?= $lets_fix_it ?></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        
                        </div>
                    </form>
                </div>
                <?php if (!is_admin()) { ?></script>
    <div id="contente">
        <plastictest></plastictest>
    </div>

    <script>
        var iframe = false;
        var nonce = '<?= wp_create_nonce('plastic_diet_form'); ?>';
        var p_id = <?= $p_id ?>;
        var form_control = <?php echo json_encode($fields_control) ?>;
        var step = 1;
        var cta_check = <?php echo (get_field('cta_check') === false ? 0 : 1) ?>;
        var short_form_check = <?php echo (get_field('short_form_check') === true ? 1 : 0) ?>;
        var country = '<?= $country->countryCode ?>';
        var signature_count = <?= json_encode($signature_count) ?>;
        var plastic_text = {
            text_1: "<?= htmlspecialchars($plastic_text) ?>",
            text_2: "<?= $plastic_text_2 ?>",
            text_3: "<?= $plastic_text_3 ?>",
            text_4: "<?= $path_2_message ?>",
        };
        var plastic_name = {
            1: {
                name: "<?php echo $button ?>",
                image: "button"
            },
            2: {
                name: "<?php echo $pen_cap ?>",
                image: "pen-cap"
            },
            3: {
                name: "<?php echo $bottle_cap ?>",
                image: "bottle-cap"
            },
            4: {
                name: "<?php echo $spoon ?>",
                image: "spoon"
            },
            5: {
                name: "<?php echo $credit_card ?>",
                image: "credit-card"
            }
        };
        var org_items = {
            water: {
                never: 0,
                occasionally: 0.2117,
                frequently: 0.8466
            },
            shellfish: {
                never: 0,
                occasionally: 0.0217,
                frequently: 0.0869
            },
            beer: {
                never: 0,
                occasionally: 0.0012,
                frequently: 0.0048
            },
            salt: {
                never: 0,
                occasionally: 0.0013,
                frequently: 0.0052
            }
        };
    </script>

<?php } ?>