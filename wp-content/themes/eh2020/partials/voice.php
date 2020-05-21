 <?php

/**
 * Voice Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

include "translations.php";

$title = "#DearSingapore, the time to shape our future is now.";
$description = "Sign this open letter to Singapore's decision makers and join our fight for a better future.";
$share_text = "#DearSingapore, our future depends on the decisions we make now. Support this open letter and help us secure a better future for all of us in Singapore.";

$mb_share_text = "#DearSingapore, our city is truly incredible. The resilience it has shown in the face of a pandemic like COVID-19 has been exemplary for the entire world. If we can come together for a health emergency, we can do the same for the planetary emergency. Support this open letter and help us secure a better future for all of us in Singapore.";
// $mb_share_text = '';

$tw_share_url = "https://twitter.com/intent/tweet?text=" . urlencode($share_text) . "&url=";
$wa_share_url =  "https://api.whatsapp.com/send?text=" . urlencode($mb_share_text);
$tl_share_url = "tg://msg_url?text=" . urlencode($mb_share_text) . "&url=";
$ln_share_url = "https://www.linkedin.com/shareArticle?mini=true&title=" . urlencode($title) . "&summary=" . urlencode($description) . "&url=";

// var_dump($translations);

?>

<script>
    let messages = <?php echo json_encode($translations); ?>
</script>

<script type="text/x-template" id="openletter-template">

    <div class="openletter-wrapper">
        <div class="d-none">{{ step }}</div>
        <div class="bg"></div>
        <div class="openletter w-100 bg-white text-dark">
            <h3 class="h6 mb-3">{{ $t('ol.intro') }}</h3>
            <p>{{ $t('ol.line1') }}</p>
            <p>{{ $t('ol.line2') }}</p>
            <p>{{ $t('ol.line3') }} <span style="text-transform: lowercase;">{{ getFeelings }}</span>.</p>
            <div v-if="step >= 2">
            <p>{{ $t('ol.line4') }}</p>
            <p>{{ $t('ol.line5') }}</p>
            <p>{{ $t('ol.line6') }}</p>
                <div v-if="data.form.issues.health_1 || data.form.issues.health_2">
                    <p>
                        <strong>{{ $t('ol.health') }}</strong><br>
                        <span v-if="data.form.issues.health_1">{{ $t('ol.health1') }}</span>
                        <span v-if="data.form.issues.health_2">{{ $t('ol.health2') }}</span>
                    </p>
                </div>
                <div v-if="data.form.issues.qualityOfLiving_1 || data.form.issues.qualityOfLiving_2">
                    <p>
                        <strong>{{ $t('ol.quality') }}</strong><br>
                        <span v-if="data.form.issues.qualityOfLiving_1">{{ $t('ol.quality1') }}</span>
                        <span v-if="data.form.issues.qualityOfLiving_2">{{ $t('ol.quality2') }}</span>
                    </p>
                </div>
                <div v-if="data.form.issues.future_1 || data.form.issues.future_2">
                    <p>
                        <strong>{{ $t('ol.future') }}</strong><br>
                        <span v-if="data.form.issues.future_1">{{ $t('ol.future1') }}</span>
                        <span v-if="data.form.issues.future_2">{{ $t('ol.future2') }}</span>
                    </p>
                </div>
                <p v-if="data.form.issues.custom_issue"><strong>{{ data.form.issues.custom_issue }}</strong></p>
            </div>
            <div v-if="step >= 3">
                <p>{{ $t('ol.line7') }}</p>
                <p>{{ $t('ol.line8') }}</p>
                <p>{{ $t('ol.signature') }} <br>{{ data.form.first_name }} {{ data.form.last_name }}</p>
            </div>
        </div>
    </div>
</script>

<script type="text/x-template" id="voice-template">
    <div id="voice-component" class="voice-form m-0" :class="['step-' + step]">
        <form id="voice-form" class="d-flex align-items-center justify-content-center">
            <div id="" action='' class="px-2 p-md-4 p-lg-5 w-100">

                <div class="d-none">{{ this.someVariable }}</div>

                <div id="step0" class="container" v-show="isStep(0)">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="mb-4 ">

                                <div class="btn-group-toggle">
                                    <label class="btn text-white no-hover mr-   " :class="this.locale == 'en' ? 'btn-gradient': 'btn-outline-gradient'">
                                        <input type="radio" name="lang" value="en" v-model="locale" id="lang_en" checked> EN
                                    </label>
                                    <label class="btn text-white no-hover mr-   " :class="this.locale == 'cn' ? 'btn-gradient': 'btn-outline-gradient'">
                                        <input type="radio" name="lang" value="cn" v-model="locale" id="lang_cn" checked> CN
                                    </label>
                                    <label class="btn text-white no-hover mr-   " :class="this.locale == 'ml' ? 'btn-gradient': 'btn-outline-gradient'">
                                        <input type="radio" name="lang" value="ml" v-model="locale" id="lang_ml" checked> ML
                                    </label>
                                    <label class="btn text-white no-hover mr-   " :class="this.locale == 'tl' ? 'btn-gradient': 'btn-outline-gradient'">
                                        <input type="radio" name="lang" value="tl" v-model="locale" id="lang_tl" checked> TL
                                    </label>
                                </div>

                                <br>
                                <h2 class="mb-">{{ $t('step0.line1') }}</h2>
                                <p>{{ $t('step0.line2') }}</p>
                                <p>{{ $t('step0.line3') }}</p>
                                <p>{{ $t('step0.line4') }}</p>
                            </div>
                            <div class="mt-4">
                                <button id="first-button" type="button" class="btn btn-gradient text-white" @click="nextStep">
                                    <span>{{ $t('step0.next_text') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <img class="w-100" src="<?php echo get_stylesheet_directory_uri() ?>/imgs/signature.png" alt="">
                        </div>
                    </div>
                    <div class="d-none text-center show-on-dearsg">
                        <h2>Let’s make decision makers pay attention</h2>
                        <p>How? By writing a letter to decision makers in Singapore. Our political leaders, businesses, institutions and schools.</p>
                        <p>In our letter, we’ll urge Singapore to improve policies and practices.</p>
                        <p>You will not be alone. We will get as many people as possible to write this letter, creating the largest voice for nature in Singapore.</p>
                            <div class="mt-4">
                                <button type="button" class="btn btn-gradient text-white" @click="nextStep">
                                    <span>Write Your Future Now</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </button>
                            </div>
                    </div>
                </div>

                <div id="step1" class="container" v-show="isStep(1)">
                    <div class="row align-items-center">
                        <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-0">
                            <div class="mb-3 mb-md-3">
                                <p>
                                    <strong>{{ $t(`step1.step1`) }}</strong>
                                    <br>
                                    <span class="step-pills">
                                        <span class="step-pill" :class="{active: step >= 1}"></span>
                                        <span class="step-pill" :class="{active: step >= 2}"></span>
                                        <span class="step-pill" :class="{active: step >= 3}"></span>
                                        <span class="step-pill" :class="{active: step >= 4}"></span>
                                    </span>
                                </p>
                                <h3>{{ $t(`step1.line1`) }}</h3>
                                <p>{{ $t(`step1.line2`) }}</p>
                                
                                <div ref="feelings" class="feelings">
                                    <input type="button" class="btn btn-outline-gradient text-white mr-2 mb-2" :class="{active: selected}" v-for="(selected, name) in feelin" :value="getFeelingName(name)" :key="name" @click="updateFeeling(name)" style="text-transform: none !important;">
                                    
                                    <div class="custom-feeling rounded input-group mb-2 text-white btn-gradient" style="max-width: 200px;">
                                        <input ref="custom_feeling" type="text" class="form-control selected text-left rounded-left border-0 bg-black" @keyup.enter="addFeeling" pattern="[A-Za-z]" maxlength="20" style="text-transform: none !important; background: #000 !important; color: #fff !important; ">
                                        <div class="input-group-append">
                                            <button class="bg-black text-white border-0 btn px-3 py-1" type="button" id="button-addon2" @click="addFeeling">&rarr;</button>
                                        </div>
                                    </div>

                                    <input type="button" class="handler btn btn-outline-gradient text-white mr-2 mb-2" @click="openCustomFeeling" value="+" />
                                </div>

                                <p class="error" v-if="errors.feelings">{{ errors.feelings }}</p>
                            </div>

                            <div class="mt-5">
                                <button  type="button" class="btn btn-lg btn-block btn-gradient text-white" @click="nextStep" >
                                    <span>{{ $t(`next`) }}</span>
                                </button>
                                <a :disabled="loading" class="text-center d-block mt-3 text-white" href="https://www.earthhour.sg/">
                                    <span>{{ $t(`back`) }}</span>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-6 mt-5 mt-lg-0">
                            <openletter :feelings="this._data.form.feelings" :data="this._data" :step="this._data.step"></openletter>
                        </div>
                    </div>
                </div>

                <div id="step2" class="container" v-show="isStep(2)">
                    <div class="row align-items-center">
                        <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-0 mb-5 mb-lg-0">
                            <div class="mb-3 mb-md-3">
                                <p>
                                    <strong>{{ $t(`step2.step2`) }}</strong>
                                    <br>
                                    <span class="step-pills">
                                        <span class="step-pill" :class="{active: step >= 1}"></span>
                                        <span class="step-pill" :class="{active: step >= 2}"></span>
                                        <span class="step-pill" :class="{active: step >= 3}"></span>
                                        <span class="step-pill" :class="{active: step >= 4}"></span>
                                    </span>
                                </p>
                                <h2>{{ $t(`step2.line1`) }}</h2>
                                <p>{{ $t(`step2.line2`) }}</p>
                                <div>
                                    <p class="error" v-if="errors.issues">{{ errors.issues }}</p>
                                    <div class="form-check issue-wrapper active">
                                        <input @change="openissue" type="checkbox" class="form-check-input" v-model="form.issues.health" id="health" value="health">
                                        <label class="form-check-label" for="health">{{ $t(`step2.health`) }}</label>
                                        <div class="issue-details mt-2">
                                            <div class="form-check issue">
                                                <input class="form-check-input" type="checkbox" v-model="form.issues.health_1" value="With the air I breathe being free from haze" id="health_1">
                                                <label class="form-check-label" for="health_1">
                                                {{ $t(`step2.health1`) }}
                                                </label>
                                            </div>
                                            <div class="form-check issue">
                                                <input class="form-check-input" type="checkbox" v-model="form.issues.health_2" value="With the food I eat being free of microplastics" id="health_2">
                                                <label class="form-check-label" for="health_2">
                                                {{ $t(`step2.health2`) }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-check issue-wrapper active">
                                        <input @change="openissue" type="checkbox" class="form-check-input" v-model="form.issues.qualityOfLiving" id="qualityOfLiving" value="qualityOfLiving">
                                        <label class="form-check-label" for="qualityOfLiving" >{{ $t(`step2.quality`) }}</label>
                                        <div class="issue-details mt-2">
                                            <div ref="issues" class="form-check issue">
                                                <input class="form-check-input" type="checkbox" v-model="form.issues.qualityOfLiving_1" value="" id="qualityOfLiving_1">
                                                <label class="form-check-label" for="qualityOfLiving_1">
                                                    {{ $t(`step2.quality1`) }}
                                                </label>
                                            </div>
                                            <div class="form-check issue">
                                                <input class="form-check-input" type="checkbox" v-model="form.issues.qualityOfLiving_2" value="" id="qualityOfLiving_2">
                                                <label class="form-check-label" for="qualityOfLiving_2">
                                                    {{ $t(`step2.quality2`) }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-check issue-wrapper active">
                                        <input @change="openissue" type="checkbox" class="form-check-input" v-model="form.issues.future" id="future" value="future">
                                        <label class="form-check-label" for="future" >{{ $t(`step2.future`) }}</label>
                                        <div class="issue-details mt-2">
                                            <div class="form-check issue">
                                                <input class="form-check-input" type="checkbox" v-model="form.issues.future_1" value="" id="future_1">
                                                <label class="form-check-label" for="future_1">
                                                {{ $t(`step2.future1`) }}
                                                </label>
                                            </div>
                                            <div class="form-check issue">
                                                <input class="form-check-input" type="checkbox" v-model="form.issues.future_2" value="" id="future_2">
                                                <label class="form-check-label" for="future_2">
                                                {{ $t(`step2.future2`) }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <br>
                                    
                                    <label for="custom_issue">{{ $t(`step2.other`) }}</label>
                                    <textarea ref="custom_issue" class="mt-2 w-100 p-2" style="outline: 0" v-model="form.issues.custom_issue" maxlength="120" cols="30" rows="3" placeholder="Add your own message"></textarea>
                                    <small>{{ 120 - form.issues.custom_issue.length }} {{ $t(`step2.char_left`) }}.</small>
                                    <p class="error" v-if="errors.issues">{{ errors.issues }}</p>
                                </div>
                            </div>

                            <div class="">
                                <button type="button" class="btn btn-lg btn-block btn-gradient text-white" @click="nextStep">
                                    <span>{{ $t(`next`) }}</span>
                                </button>
                                <a :disabled="loading" class="text-center d-block mt-3 text-white" @click="prevStep">
                                    <span>{{ $t(`back`) }}</span>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-6 ">
                            <openletter :feelings="this._data.form.feelings" :data="this._data" :step="this._data.step"></openletter>
                        </div>
                    </div>
                </div>

                <div id="step3" class="container" v-show="isStep(3)">
                    <div class="row  align-items-center">
                        <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-0">
                            <p>
                                <strong>{{ $t(`step3.step3`) }}</strong>
                                <br>
                                <span class="step-pills">
                                    <span class="step-pill" :class="{active: step >= 1}"></span>
                                    <span class="step-pill" :class="{active: step >= 2}"></span>
                                    <span class="step-pill" :class="{active: step >= 3}"></span>
                                    <span class="step-pill" :class="{active: step >= 4}"></span>
                                </span>
                            </p>
                            <h3>{{ $t(`step3.line1`) }}</h3>
                            <p>{{ $t(`step3.line2`) }}</p>
                            <div class="row my-3">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="first_name">{{ $t(`step3.first_name`) }} <span>*</span></label>
                                        <input ref="first_name" id="first_name" :disabled="loading" type="name" class="form-control" v-model="form.first_name" :placeholder="$t(`step3.first_name`)">
                                        <p class="error my-2" v-if="errors.first_name">Please enter a valid first name.</p>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="last_name">{{ $t(`step3.last_name`) }} <span>*</span></label>
                                        <input ref="last_name" id="last_name" :disabled="loading" type="name" class="form-control" v-model="form.last_name" :placeholder="$t(`step3.last_name`)">
                                        <p class="error my-2" v-if="errors.last_name">Please enter a valid last name.</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="email">{{ $t(`step3.email`) }} <span>*</span></label>
                                        <input ref="email" id="email" :disabled="loading" type="email" class="form-control" v-model="form.email" :placeholder="$t(`step3.email`)">
                                        <p class="error my-2" v-show="errors.email">Please enter a valid email.</p>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="phone">{{ $t(`step3.mobile`) }}</label>
                                        <VuePhoneNumberInput ref="phone" id="phone" :no-flags=true :translations="{countrySelectorLabel: 'Country code', countrySelectorError: 'Choose a country', phoneNumberLabel: 'Phone number', example: 'Example : '}" :disabled="loading" v-model="form.phone" :default-country-code="form.phone_country" @update="onUpdatePhone" :key="form.country" color="#000000" valid-color="#000000" error-color="#000000" />
                                        <!-- <input :disabled="loading" type="number" class="form-control" v-model="form.phone" placeholder="Your mobile number"> -->
                                        <p class="error my-2" v-show="errors.phone">Please enter a valid phone.</p>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="age">{{ $t(`step3.age`) }} <span>*</span></label>
                                        <select ref="age" :disabled="loading" class="form-control bg-white" v-model="form.age"  id="age">
                                            <option value="">- {{ $t(`step3.age`) }} -</option>
                                            <option value="Below 18">{{ $t(`below`) }}</option>
                                            <option value="18-24">18-24</option>
                                            <option value="25-35">25-35</option>
                                            <option value="36-50">36-50</option>
                                            <option value="51-69">51-69</option>
                                            <option value="70 and above">{{ $t(`above`) }}</option></select>
                                        </select>
                                        <p class="error my-2" v-show="errors.age">{{ errors.age }}</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="citizen">{{ $t(`step3.citizen`) }}</label>
                                    <div class="mb-2">
                                        <button type="button" @click="updateCitizen('singaporean')"  class="btn text-white mr-2 mb-2 no-hover" :class="{'btn-gradient': this.form.citizen == 'singaporean', 'btn-outline-gradient': this.form.citizen != 'singaporean'}">{{ $t(`step3.singaporean`) }}</button>
                                        <button type="button" @click="updateCitizen('non-singaporean')" class="btn text-white no-hover" :class="{'btn-gradient': this.form.citizen == 'non-singaporean', 'btn-outline-gradient': this.form.citizen != 'non-singaporean'}">{{ $t(`step3.nonsingaporean`) }}</button>
                                    </div>
                                </div>
                                <div class="col-12" v-if="this.form.citizen == 'singaporean'">
                                    <div class="form-group">
                                        <label for="postalcode">{{ $t(`step3.postalcode`) }}</label>
                                        <input ref="postalcode" id="postalcode" :disabled="loading" type="number" class="form-control" v-model="form.postalcode" :placeholder="$t(`step3.postalcode`)">
                                        <p class="error my-2" v-show="errors.postalcode">Please enter a valid email.</p>
                                    </div>
                                </div>
                                <div class="col-12" v-if="this.form.citizen != 'singaporean'">
                                    <label for="country">{{ $t(`step3.country`) }}</label>
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
                                    <label class="custom-control-label pt-1" for="check_pdpc" v-html="$t(`step3.pdpa`)">
                                        <!-- {{ $t(`step3.pdpa`) }} -->
                                        <!-- By submitting this form, you agree to WWF’s <a href="https://www.wwf.sg/wwf_singapore/pdp_policy/" target="_blank">Privacy Policy</a> and Terms and Conditions. By accepting, you acknowledge and consent to WWF sending you such updates. -->
                                    </label>
                                </div>
                                
                                <div class="mt-2">
                                    <p class="error mb-0" v-show="errors.check_pdpc">Please select the checkbox above to continue.</p>
                                </div>
                            </div>
                            <p class="error mt-2 text-center" v-show="errors.random">{{ errors.random }}</p>
                            <div class="text-md-left mt-3">
                                <button :disabled="loading" type="button" class="btn-block btn btn-lg btn-gradient text-white" @click="nextStep">
                                    <span>{{ $t(`step3.cta`) }}</span>
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
                                    <span>{{ $t(`back`) }}</span>
                                </a>
                            </div>
                            
                        </div>

                        <div class="col-lg-6 d-none d-lg-block">
                            <openletter :feelings="this._data.form.feelings" :data="this._data" :step="this._data.step"></openletter>
                        </div>
                    </div>
                </div>

                <div id="step4" v-show="step == 4">
                    <div class="container">
                        <h2>{{ $t(`step4.line1`) }}</h2>
                        <p>{{ $t(`step4.line2`) }}</p>
                        <p v-if="locale !== 'en'">{{ $t(`step4.line6`) }}</p>
                        <p>{{ $t(`step4.line3`) }}<p>

                        <div class="row mt-4">
                            <!-- <div class="col-6 col-sm-5 col-md-3">
                                <h6 class="sub-heading">{{ $t(`step4.line4`) }}</h6>
                                <a class="btn btn-outline-gradient p-2 pt-2" download href="https://www.earthhour.sg/wp-content/uploads/2020/03/openletter.png" title="Open Letter">
                                    <svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0)">
                                            <path d="M11.5002 18.0715L18.893 10.6786L16.4288 8.21433L13.1431 11.5V0H9.85738V11.5L6.57169 8.21433L4.10742 10.6786L11.5002 18.0715Z" fill="white"/>
                                            <path d="M0 20.0714H23V23.3571H0V20.0714Z" fill="white"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0">
                                                <rect width="23" height="23" fill="white"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </a>
                            </div> -->
                            <div class="col-8 mt-4 mt-md-0">
                                <h6 class="sub-heading">{{ $t(`step4.line5`) }}</h6>
                                <a style="backgroundcolor: rgb(71, 89, 147);" class="btn-social mb-2 btn btn-outline-dark text-white border-0" :href="'https://www.facebook.com/sharer/sharer.php?u=' + shareUrl" target="_blank">
                                    <svg width="22px" height="22px" viewBox="0 0 22 22" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g id="Open-Letter" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Step-4" transform="translate(-51.000000, -520.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                <g id="facebook-(1)" transform="translate(51.000000, 520.000000)">
                                                    <path d="M19.25,0 L2.75,0 C1.233375,0 0,1.233375 0,2.75 L0,19.25 C0,20.766625 1.233375,22 2.75,22 L11,22 L11,14.4375 L8.25,14.4375 L8.25,11 L11,11 L11,8.25 C11,5.971625 12.846625,4.125 15.125,4.125 L17.875,4.125 L17.875,7.5625 L16.5,7.5625 C15.741,7.5625 15.125,7.491 15.125,8.25 L15.125,11 L18.5625,11 L17.1875,14.4375 L15.125,14.4375 L15.125,22 L19.25,22 C20.766625,22 22,20.766625 22,19.25 L22,2.75 C22,1.233375 20.766625,0 19.25,0 Z" id="Path"></path>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </a>
                                <a style="backgroundcolor: #76A9EA;" class="btn-social mb-2 btn btn-outline-dark text-white border-0" :href="'<?= $tw_share_url ?>' + shareUrl" target="_blank">
                                    <svg width="24px" height="19px" viewBox="0 0 24 19" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g id="Open-Letter" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Step-4" transform="translate(-89.000000, -523.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                <g id="twitter-(1)" transform="translate(89.000000, 523.000000)">
                                                    <path d="M24,2.24930769 C23.1075,2.63076923 22.1565,2.88361538 21.165,3.00638462 C22.185,2.413 22.9635,1.48053846 23.3295,0.356615385 C22.3785,0.909076923 21.3285,1.29930769 20.2095,1.51707692 C19.3065,0.580230769 18.0195,0 16.6155,0 C13.8915,0 11.6985,2.15430769 11.6985,4.79530769 C11.6985,5.17530769 11.7315,5.54069231 11.8125,5.88853846 C7.722,5.69415385 4.1025,3.78392308 1.671,0.874 C1.2465,1.59161538 0.9975,2.413 0.9975,3.29723077 C0.9975,4.95753846 1.875,6.42930769 3.183,7.28138462 C2.3925,7.26676923 1.617,7.04315385 0.96,6.69092308 C0.96,6.70553846 0.96,6.72453846 0.96,6.74353846 C0.96,9.07323077 2.6655,11.0083077 4.902,11.4540769 C4.5015,11.5607692 4.065,11.6119231 3.612,11.6119231 C3.297,11.6119231 2.979,11.5943846 2.6805,11.5300769 C3.318,13.4286154 5.127,14.8243846 7.278,14.8696923 C5.604,16.1456154 3.4785,16.9143846 1.1775,16.9143846 C0.774,16.9143846 0.387,16.8968462 0,16.8486154 C2.1795,18.2180769 4.7625,19 7.548,19 C16.602,19 21.552,11.6923077 21.552,5.358 C21.552,5.14607692 21.5445,4.94146154 21.534,4.73830769 C22.5105,4.06307692 23.331,3.21976923 24,2.24930769 Z" id="Path"></path>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </a>
                                <a style="backgroundcolor: #7AD06D;" class="btn-social mb-2 btn btn-outline-dark text-white border-0" :href="'<?= $wa_share_url ?> ' + shareUrl" target="_blank">
                                    <svg width="22px" height="22px" viewBox="0 0 22 22" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g id="Open-Letter" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Step-4" transform="translate(-168.000000, -520.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                <g id="whatsapp-(1)" transform="translate(168.000000, 520.000000)">
                                                    <path d="M11.00275,0 L10.99725,0 C4.932125,0 0,4.9335 0,11 C0,13.40625 0.7755,15.6365 2.094125,17.447375 L0.72325,21.533875 L4.951375,20.18225 C6.69075,21.3345 8.765625,22 11.00275,22 C17.067875,22 22,17.065125 22,11 C22,4.934875 17.067875,0 11.00275,0 Z M17.403375,15.533375 C17.138,16.28275 16.08475,16.90425 15.244625,17.08575 C14.669875,17.208125 13.919125,17.30575 11.391875,16.258 C8.15925,14.91875 6.0775,11.633875 5.91525,11.42075 C5.759875,11.207625 4.609,9.681375 4.609,8.102875 C4.609,6.524375 5.410625,5.75575 5.73375,5.42575 C5.999125,5.154875 6.43775,5.031125 6.8585,5.031125 C6.994625,5.031125 7.117,5.038 7.227,5.0435 C7.550125,5.05725 7.712375,5.0765 7.9255,5.586625 C8.190875,6.226 8.837125,7.8045 8.914125,7.96675 C8.9925,8.129 9.070875,8.349 8.960875,8.562125 C8.85775,8.782125 8.767,8.87975 8.60475,9.06675 C8.4425,9.25375 8.2885,9.39675 8.12625,9.5975 C7.97775,9.772125 7.81,9.959125 7.997,10.28225 C8.184,10.5985 8.83025,11.653125 9.78175,12.500125 C11.009625,13.59325 12.005125,13.9425 12.36125,14.091 C12.626625,14.201 12.942875,14.174875 13.13675,13.968625 C13.382875,13.70325 13.68675,13.26325 13.996125,12.830125 C14.216125,12.519375 14.493875,12.480875 14.785375,12.590875 C15.082375,12.694 16.654,13.470875 16.977125,13.63175 C17.30025,13.794 17.513375,13.871 17.59175,14.007125 C17.66875,14.14325 17.66875,14.782625 17.403375,15.533375 Z" id="Shape"></path>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </a>
                                <a style="backgroundcolor: #039be5;" class="btn-social mb-2 btn btn-outline-dark text-white border-0" :href="'<?= $tl_share_url ?>' + shareUrl" target="_blank">
                                    <svg width="22px" height="19px" viewBox="0 0 22 19" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g id="Open-Letter" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Step-4" transform="translate(-206.000000, -523.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                <g id="telegram-(1)" transform="translate(206.000000, 523.000000)">
                                                    <path d="M5.29354856,11.0089496 L8.02978516,17.7301996 L11.5919952,14.2298785 L17.6996002,19 L22,0 L0,9.00454861 L5.29354856,11.0089496 Z M15.7134704,5.45128472 L8.97962953,11.4865885 L8.14089968,14.5927257 L6.59150697,10.7857986 L15.7134704,5.45128472 Z" id="Shape"></path>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </a>
                                <a style="backgroundcolor: #0077B7" class="btn-social mb-2 btn btn-outline-dark text-white border-0" :href="'<?= $ln_share_url ?>' + shareUrl" target="_blank">
                                    <svg width="23px" height="23px" viewBox="0 0 23 23" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g id="Open-Letter" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Step-4" transform="translate(-129.000000, -519.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                                <g id="linkedin-(1)" transform="translate(129.000000, 519.000000)">
                                                    <rect id="Rectangle" x="0" y="7" width="5" height="16"></rect>
                                                    <path d="M19.1076364,7.18763636 C19.0523636,7.17018182 19,7.15127273 18.9418182,7.13527273 C18.872,7.11927273 18.8021818,7.10618182 18.7309091,7.09454545 C18.4545455,7.03927273 18.152,7 17.7970909,7 C14.7629091,7 12.8385455,9.20654545 12.2043636,10.0589091 L12.2043636,7 L7,7 L7,23 L12.2043636,23 L12.2043636,14.2727273 C12.2043636,14.2727273 16.1374545,8.79490909 17.7970909,12.8181818 C17.7970909,16.4094545 17.7970909,23 17.7970909,23 L23,23 L23,12.2029091 C23,9.78545455 21.3432727,7.77090909 19.1076364,7.18763636 Z" id="Path"></path>
                                                    <circle id="Oval" cx="2.5" cy="2.5" r="2.5"></circle>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="share-image mt-5">
                            <img  v-show="!image_loading" class="w-100" :src="shareImage" alt="">
                        </div>

                        <div class="mt-5 pt-5">
                            <!-- <openletter :feelings="this._data.form.feelings" :data="this._data" :step="this._data.step"></openletter> -->
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</script>

<div id="contente">
    <plastictest></plastictest>

    <script>
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        var nonce = '<?= wp_create_nonce('voice_form'); ?>';
        var utm_campaign = '<?php echo (isset($_GET['utm_campaign']) && !empty($_GET['utm_campaign'])) ? $_GET['utm_campaign'] : '' ?>';
        var utm_source = '<?php echo (isset($_GET['utm_source']) && !empty($_GET['utm_source'])) ? $_GET['utm_source'] : '' ?>';
        var utm_medium = '<?php echo (isset($_GET['utm_medium']) && !empty($_GET['utm_medium'])) ? $_GET['utm_medium'] : '' ?>';
        var utm_content = '<?php echo (isset($_GET['utm_content']) && !empty($_GET['utm_content'])) ? $_GET['utm_content'] : '' ?>';
        var utm_term = '<?php echo (isset($_GET['utm_term']) && !empty($_GET['utm_term'])) ? $_GET['utm_term'] : '' ?>';
    </script>
</div>