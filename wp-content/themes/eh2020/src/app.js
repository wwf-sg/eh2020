window.Vue = require("vue");
import v8n from "v8n";
import axios from "axios";
import tippy from "tippy.js";

// import vSelect from "vue-select";
// import "vue-select/dist/vue-select.css";
// Vue.component("v-select", vSelect);

import VuePhoneNumberInput from "vue-phone-number-input";
import "vue-phone-number-input/dist/vue-phone-number-input.css";
Vue.component("vue-phone-number-input", VuePhoneNumberInput);

// import TelInput from "vue-tel-input";
// import "vue-tel-input/dist/vue-tel-input.css";
// Vue.component("vue-tel-input", TelInput);

// require("jquery");
require("bootstrap");

Vue.component("v-style", {
  render: function(createElement) {
    return createElement("style", this.$slots.default);
  }
});
// Register a global custom directive called v-focus
Vue.directive("focus", {
  // When the bound element is inserted into the DOM...
  inserted: function(el) {
    // Focus the element
    el.focus();
  }
});
window.v = v8n;
v8n.extend({
  email: expected => {
    return function(value) {
      if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)) {
        return true;
      }
      return false;
    };
  }
});

var app = new Vue({
  el: "#contente",
  template: "#plastictest-template",
  components: {
    VuePhoneNumberInput
  },
  data: function() {
    return {
      iframe: iframe,
      _nonce: nonce,
      loading: false,
      image_loading: false,
      path: 1,
      p_id: p_id,
      s_id: "",
      signatureCount: signature_count,
      shareImage: "",
      shareUrl: "https://yourplasticdiet.org/",
      picked: "",
      someVariable: 0,
      step: step,
      cta_check: cta_check,
      short_form_check: short_form_check,
      maxSteps: 8,
      plastic_text: "",
      plastic_text_args: plastic_text,
      form: {
        first_name: "",
        last_name: "",
        name: "",
        country: country,
        email: "",
        phone_withcc: "",
        phone: "",
        age: "",
        message: "",
        plastic_value: 0.01,
        plastic_name: {
          name: "Button",
          image: "button"
        },
        marketing: false,
        items: {
          water: "",
          shellfish: "",
          beer: "",
          salt: ""
        }
      },
      errors: {},
      form_control: form_control
    };
  },
  mounted: function() {},
  methods: {
    newMethod: function(url) {
      parent.postMessage(
        {
          url: "url"
        },
        "*"
      );
    },
    isStep: function(step) {
      if (step >= 5) {
        return this.cta_check && this.step === step;
      }
      return this.step === step;
    },
    doPlasticMath() {
      let item = this.form.items;

      // per day
      let plastic_value_day =
        org_items.water[item.water] +
        org_items.shellfish[item.shellfish] +
        org_items.beer[item.beer] +
        org_items.salt[item.salt];

      // per week
      let plastic_value_week = plastic_value_day * 7;

      this.form.plastic_value = Math.round(plastic_value_week * 100) / 100;

      switch (Math.ceil(this.form.plastic_value)) {
        case 0:
          this.form.plastic_name = "None";
          break;
        case 1:
          this.form.plastic_name = plastic_name[1];
          break;
        case 2:
          this.form.plastic_name = plastic_name[2];
          break;
        case 3:
          this.form.plastic_name = plastic_name[3];
          break;
        case 4:
          this.form.plastic_name = plastic_name[4];
          break;
        case 5:
          this.form.plastic_name = plastic_name[5];
          break;
        default:
          this.form.plastic_name = plastic_name[5];
          break;
      }
      return this.form;
    },
    setPlasticText() {
      if (this.path == 1) {
        this.plastic_text = this.plastic_text_args["text_1"] + "<hr>";

        if (this.form.plastic_value < 6) {
          this.plastic_text += this.plastic_text_args["text_2"];
        } else {
          this.plastic_text += this.plastic_text_args["text_3"];
        }
      } else {
        this.plastic_text = this.plastic_text_args["text_4"];
      }
      return this.plastic_text;
    },
    nextStep() {
      const forme = document.getElementById("take-the-plastic-test");
      this.errors = {};
      switch (this.step) {
        case 1:
          if (
            !v8n()
              .string()
              .minLength(3)
              .test(this.form.name)
          ) {
            this.errors.name = "Please enter a valid name";
            forme.scrollIntoView();
            return this.errors;
          }
          break;
        case 2:
          if (
            !v8n()
              .string()
              .minLength(1)
              .test(this.form.country)
          ) {
            this.errors.country = "Please select a country";
            forme.scrollIntoView();
            return this.errors;
          }
          break;
        case 3:
          let item = this.form.items;
          if (!(item.water && item.shellfish && item.beer && item.salt)) {
            this.errors.items = "Please select the options.";
            forme.scrollIntoView();
            return this.errors;
          }
          this.doPlasticMath();
          break;
        case 4:
          if (this.cta_check && !this.short_form_check) {
          } else {
            // if the cta is not used do this here
            this.setPlasticText();

            var data = {
              action: "getSignature",
              data: this._data,
              _nonce: this._data._nonce
            };
            var that = this;

            if (!this.short_form_check) {
              this.loading = true;
            }
            this.image_loading = true;

            jQuery.ajax({
              type: "POST",
              url: ajaxurl,
              data: data,
              success: function(results) {
                var data = jQuery.parseJSON(results);
                that.shareImage = data.image_url;
                that.shareUrl = data.share_url;
                that.s_id = data.s_id;
                that.loading = false;
                that.image_loading = false;
                that.signatureCount = data.signatureCount;

                if (!that.short_form_check) {
                  gtag("event", "step", {
                    event_category: "plastic_diet_step",
                    event_label: this.step,
                    transport_type:
                      document.location.host + document.location.pathname
                  });

                  forme.scrollIntoView();
                  // uncomment this if the loading screen needs to be on step 4
                  return (that.step =
                    that.step < that.maxSteps ? 8 : that.step);
                }
              },
              error: function(errorThrown) {
                that.loading = false;
                that.image_loading = false;
                console.error("Error: Could not submit");
              }
            });
            setTimeout(function() {
              that.loading = false;
              that.image_loading = false;
            }, 15000);

            if (!this.short_form_check) {
              return this.errors;
            } else {
              gtag("event", "step", {
                event_category: "plastic_diet_step",
                event_label: this.step,
                transport_type:
                  document.location.host + document.location.pathname
                // event_callback: function() {
                //     document.location = url;
                // }
              });
              return (this.step =
                this.step < this.maxSteps ? this.step + 1 : this.step);
            }
          }
          break;
        case 5:
          if (this.short_form_check) {
            if (
              !v8n()
                .string()
                .email()
                .minLength(3)
                .test(this.form.email)
            ) {
              this.errors.email = "Please enter a valid email";
            }

            if (
              form_control.phone_field.use &&
              form_control.phone_field.required
            ) {
              if (!this.form.phonea.isValid) {
                this.errors.phone = "Please enter a valid phone";
              }
            }

            if (form_control.age_field.use && form_control.age_field.required) {
              if (
                !v8n()
                  .string()
                  // .number()
                  // .greaterThan(12)
                  // .lessThan(99)
                  .minLength(3)
                  .test(this.form.age)
              ) {
                this.errors.age = "Please enter a valid age.";
              }
            }

            // CUSTOM MESSAGE
            if (
              form_control.message_field.use &&
              form_control.message_field.required
            ) {
              if (
                !v8n()
                  .string()
                  .test(this.form.message)
              ) {
                this.errors.message = "Please enter a valid message";
              }
            }

            // MARKETING
            if (
              form_control.marketing_field.use &&
              form_control.marketing_field.required
            ) {
              if (!this.form.marketing) {
                this.errors.marketing = "Please select the option.";
              }
            }

            if (Object.keys(this.errors).length) {
              const email = document.getElementById("email");
              // .focus()
              email.scrollIntoView();
              return this.errors;
            }

            this.setPlasticText();

            var data = {
              action: "getSignature",
              data: this._data,
              _nonce: this._data._nonce
            };
            this.loading = true;
            this.image_loading = true;
            var that = this;

            jQuery.ajax({
              type: "POST",
              url: ajaxurl,
              data: data,
              success: function(results) {
                var data = jQuery.parseJSON(results);
                that.shareImage = data.image_url;
                that.shareUrl = data.share_url;
                that.signatureCount = data.signatureCount;
                that.loading = false;
                that.image_loading = false;

                gtag("event", "step", {
                  event_category: "plastic_diet_step",
                  event_label: this.step,
                  transport_type:
                    document.location.host + document.location.pathname
                });

                forme.scrollIntoView();
                return (that.step = that.step < that.maxSteps ? 8 : that.step);
              },
              error: function(errorThrown) {
                that.loading = false;
                that.image_loading = false;
                console.error("Error: Could not submit");
              }
            });
            setTimeout(function() {
              that.loading = false;
              that.image_loading = false;
            }, 15000);
            return this.errors;
          } else {
            // if the cta is not used do this here
            this.setPlasticText();

            var data = {
              action: "getSignature",
              data: this._data,
              _nonce: this._data._nonce
            };
            var that = this;

            jQuery.ajax({
              type: "POST",
              url: ajaxurl,
              data: data,
              success: function(results) {
                var data = jQuery.parseJSON(results);
                that.shareImage = data.image_url;
                that.shareUrl = data.share_url;
                that.s_id = data.s_id;
                that.signatureCount = data.signatureCount;
              },
              error: function(errorThrown) {
                console.error("Error: Could not submit");
              }
            });
          }
          break;
        case 7:
          if (
            !v8n()
              .string()
              .minLength(3)
              .test(this.form.name)
          ) {
            this.errors.name = "Please enter a valid name";
          }

          if (
            !v8n()
              .string()
              .minLength(1)
              .test(this.form.country)
          ) {
            this.errors.country = "Please select a country";
          }

          if (
            !v8n()
              .string()
              .email()
              .minLength(3)
              .test(this.form.email)
          ) {
            this.errors.email = "Please enter a valid email";
          }

          if (
            form_control.phone_field.use &&
            form_control.phone_field.required
          ) {
            if (!this.form.phonea.isValid) {
              // if (
              //   !v8n()
              //     .string()
              //     // .greaterThan(10000000)
              //     // .lessThan(9999999999)
              //     .test(this.form.phone)
              // ) {
              // delete this.error.phone;
              this.errors.phone = "Please enter a valid phone";
            }
          }

          if (form_control.age_field.use && form_control.age_field.required) {
            if (
              !v8n()
                .string()
                // .number()
                // .greaterThan(12)
                // .lessThan(99)
                .minLength(3)
                .test(this.form.age)
            ) {
              this.errors.age = "Please enter a valid age.";
            }
          }

          // CUSTOM MESSAGE
          if (
            form_control.message_field.use &&
            form_control.message_field.required
          ) {
            if (
              !v8n()
                .string()
                .test(this.form.message)
            ) {
              this.errors.message = "Please enter a valid message";
            }
          }

          // MARKETING
          if (
            form_control.marketing_field.use &&
            form_control.marketing_field.required
          ) {
            if (!this.form.marketing) {
              this.errors.marketing = "Please select the option.";
            }
          }

          if (Object.keys(this.errors).length) {
            const email = document.getElementById("email");
            // .focus()
            email.scrollIntoView();
            return this.errors;
          }

          this.setPlasticText();

          var data = {
            action: "getSignature",
            data: this._data,
            _nonce: this._data._nonce
          };
          this.loading = true;
          this.image_loading = true;
          var that = this;

          jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: data,
            success: function(results) {
              var data = jQuery.parseJSON(results);
              that.shareImage = data.image_url;
              that.shareUrl = data.share_url;
              that.signatureCount = data.signatureCount;
              that.loading = false;
              that.image_loading = false;

              gtag("event", "step", {
                event_category: "plastic_diet_step",
                event_label: this.step,
                transport_type:
                  document.location.host + document.location.pathname
              });

              forme.scrollIntoView();
              return (that.step =
                that.step < that.maxSteps ? that.step + 1 : that.step);
            },
            error: function(errorThrown) {
              that.loading = false;
              that.image_loading = false;
              console.error("Error: Could not submit");
            }
          });
          setTimeout(function() {
            that.loading = false;
            that.image_loading = false;
          }, 15000);
          return this.errors;
          break;
      }
      forme.scrollIntoView();
      gtag("event", "step", {
        event_category: "plastic_diet_step",
        event_label: this.step,
        transport_type: document.location.host + document.location.pathname
        // event_callback: function() {
        //     document.location = url;
        // }
      });
      return (this.step =
        this.step < this.maxSteps ? this.step + 1 : this.step);
    },
    forceRerender() {
      this.someVariable += 1;
    },
    showBreathAlert() {
      alert("show");
    },
    prevStep() {
      return (this.step = this.step > 1 ? this.step - 1 : this.step);
    },
    skip() {
      gtag("event", "step", {
        event_category: "plastic_diet_step",
        event_label: this.step,
        transport_type: document.location.host + document.location.pathname
      });
      return (this.step = this.step < this.maxSteps ? 8 : this.step);
    },
    getErrorMsg(name) {
      return this.errors[name];
    },
    onUpdatePhone(payload) {
      this.form.phone_withcc = payload.formattedNumber;
      this.form.phonea = payload;
    }
  },
  mounted() {
    // console.log(this.);
  },
  computed: {
    useEmailField: function() {
      return this.cta_check && this.form_control.email_field.use;
    },
    usePhoneField: function() {
      return this.cta_check && this.form_control.phone_field.use;
    },
    useAgeField: function() {
      return this.cta_check && this.form_control.age_field.use;
    },
    useMessageField: function() {
      return this.cta_check && this.form_control.message_field.use;
    },
    useMarketingField: function() {
      return this.cta_check && this.form_control.marketing_field.use;
    }
  }
});
window.app = app;

tippy("div.tt", {
  arrow: true
});

const step6 = document.querySelectorAll("a[href='#what-can-i-do']");
for (let em of step6) {
  em.addEventListener("click", function() {
    if (app._data.step == 1) {
      app._data.step = 6;
      app._data.path = 2;
      return app._data.path;
    }
  });
}
const step1 = document.querySelectorAll("a[href='#take-the-plastic-test']");
for (let em of step1) {
  em.addEventListener("click", function() {
    if (app._data.step == 6) {
      app._data.step = 1;
      app._data.path = 1;
      return app._data.path;
    }
  });
}
