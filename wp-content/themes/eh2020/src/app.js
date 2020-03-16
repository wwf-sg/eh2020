window.Vue = require("vue");
import v8n from "v8n";
import axios from "axios";
import $ from "jquery";

// import vSelect from "vue-select";
// import "vue-select/dist/vue-select.css";
// Vue.component("v-select", vSelect);

import VuePhoneNumberInput from "vue-phone-number-input";
import "vue-phone-number-input/dist/vue-phone-number-input.css";
Vue.component("vue-phone-number-input", VuePhoneNumberInput);

// require("bootstrap");

$(document).ready(function() {
  $(".w2gm-content-fields-metabox").css("display", "block");
  $('form input[name="post_title"]').val("Map Title");
  $(".w2gm-field-input-block-11").insertAfter("#wwf-c-name");
  $(".w2gm-field-input-block-6").insertAfter("#wwf-c-mail");
  $(window).bind("load", function() {
    $("#post_content_ifr").css("height", "200px");
    $('.w2gm-location-input select.w2gm-selectmenu option[value="55"]').attr(
      "selected",
      true
    );
    $(".w2gm-location-input select.w2gm-selectmenu").attr(
      "disabled",
      "disabled"
    );
    $(".w2gm-submit-section-description .w2gm-description").insertAfter(
      "#tinymce > p"
    );
  });
  $("#w2gm-categorychecklist input").attr("checked", "checked");
  $(".w2gm-submit-section-categories").fadeOut();
  // $(".w2gm-submit-section-label:contains('Contact Information')")
  //   .parent("div")
  //   .hide();

  setInterval(function time() {
    var d = new Date();
    var target = new Date("March 28, 2020 17:30:00");
    var diffTime = Math.abs(target - d);

    var days = Math.floor(diffTime / (1000 * 60 * 60 * 24));
    var hours = (
      "0" + Math.floor((diffTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
    ).slice(-2);
    var min = (
      "0" +
      Math.floor(
        ((diffTime % (1000 * 60 * 60 * 24)) % (1000 * 60 * 60)) / (1000 * 60)
      )
    ).slice(-2);
    if ((min + "").length == 1) {
      min = "0" + min;
    }
    var sec = 60 - d.getSeconds();
    if ((sec + "").length == 1) {
      sec = "0" + sec;
    }
    $("#countdown #day").html(days);
    $("#countdown #hour").html(hours);
    $("#countdown #min").html(min);
    $("#countdown #sec").html(sec);
  }, 1000);

  /**
   * This object controls the nav bar. Implement the add and remove
   * action over the elements of the nav bar that we want to change.
   *
   * @type {{flagAdd: boolean, elements: string[], add: Function, remove: Function}}
   */
  var myNavBar = {
    flagAdd: true,

    elements: [],

    init: function(elements) {
      this.elements = elements;
    },

    add: function() {
      if (this.flagAdd) {
        for (var i = 0; i < this.elements.length; i++) {
          if (document.getElementById(this.elements[i])) {
            document.getElementById(this.elements[i]).className +=
              " fixed-theme";
          }
        }
        this.flagAdd = false;
      }
    },

    remove: function() {
      for (var i = 0; i < this.elements.length; i++) {
        const ele = document.getElementById(this.elements[i]);
        if (ele) {
          ele.classList.remove("fixed-theme");
        }
      }
      this.flagAdd = true;
    }
  };

  /**
   * Init the object. Pass the object the array of elements
   * that we want to change when the scroll goes down
   */
  myNavBar.init(["masthead"]);

  /**
   * Function that manage the direction
   * of the scroll
   */
  function offSetManager() {
    var yOffset = 0;
    var currYOffSet = window.pageYOffset;

    if (yOffset < currYOffSet) {
      myNavBar.add();
    } else if (currYOffSet === yOffset) {
      myNavBar.remove();
    }
  }

  /**
   * bind to the document scroll detection
   */
  window.onscroll = function(e) {
    offSetManager();
  };

  /**
   * We have to do a first detectation of offset because the page
   * could be load with scroll down set.
   */
  offSetManager();
});

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

Vue.component("openletter", {
  props: ["feelings", "step", "issues", "data"],
  data: function() {
    return {};
  },
  computed: {
    getFeelings: function() {
      const feelings = this.feelings;
      let count = 0;
      let out = "";
      let selected = [];

      for (const name in feelings) {
        if (feelings.hasOwnProperty(name)) {
          if (feelings[name]) {
            selected.push(name);
          }
        }
      }

      selected.forEach((element, index) => {
        count += 1;

        if (selected.length == 1) {
          out += element;
        }

        if (selected.length == 2) {
          if (index == 1) out += element;
          else out += element + " and ";
        }

        if (selected.length >= 3) {
          out += element;

          if (index !== selected.length - 2 && index !== selected.length - 1)
            out += ", ";

          if (index == selected.length - 2) out += " and ";
        }
      });

      // if none of them are slected return ...
      if (count == 0) out = "..";

      return out;
    }
  },
  template: "#openletter-template"
});

var app = new Vue({
  el: "#contente",
  template: "#voice-template",
  components: {
    VuePhoneNumberInput
  },
  data: function() {
    return {
      someVariable: 0,
      _nonce: nonce,
      loading: false,
      image_loading: false,
      signatureCount: 0,
      shareImage: "",
      shareUrl: "https://earthhour.sg/",
      step: 0,
      maxSteps: 4,
      form: {
        name: "",
        phone_withcc: "",
        phone: "",
        first_name: "",
        last_name: "",
        email: "",
        age: 0,
        // phone: "123",
        // first_name: "Manoj",
        // last_name: "hl",
        // email: "123@gmail.com",
        // age: 22,
        citizen: "singaporean",
        postalcode: "",
        country: "SG",
        check_pdpc: false,
        check_consent: false,
        feelings: {
          Anxious: false,
          Hopeful: false
        },
        issues: {
          health: false,
          health_1: false,
          health_2: false,
          future: false,
          future_1: false,
          future_2: false,
          qualityOfLiving: false,
          qualityOfLiving_1: false,
          qualityOfLiving_2: false,
          custom_issue: ""
        }
      },
      errors: {}
    };
  },
  methods: {
    forceRerender: function() {
      this.someVariable += 1;
    },
    addFeeling: function(e) {
      const feelings = { ...this.form.feelings };
      let feel = this.$refs.custom_feeling.value.trim();
      if (feel.match(/[^a-zA-Z]/g)) {
        feel = feel.replace(/[^a-zA-Z]/g, "");
      }
      if (feel != "") {
        feelings[feel] = true;
        this.form.feelings = feelings;

        this.$refs.custom_feeling.parentElement.parentElement.classList.remove(
          "active"
        );
        this.loading = false;
      } else {
        // this.error["feelings"] = "Please enter";
      }

      this.forceRerender();
    },
    updateCitizen: function(value) {
      this.form.citizen = value;
    },
    updateFeeling(name) {
      this.form.feelings[name] = !this.form.feelings[name];

      this.forceRerender();
    },
    openCustomFeeling: function(e) {
      e.target.parentElement.classList.add("active");
      this.loading = true;
      this.$refs["custom_feeling"].value = "";
      this.$refs["custom_feeling"].focus();
    },
    openissue: function(e) {
      e.target.parentElement.classList.add("active");
      if (e.target.checked) {
        this.form.issues[e.target.value + "_1"] = true;
        this.form.issues[e.target.value + "_2"] = true;
      } else {
        this.form.issues[e.target.value + "_1"] = false;
        this.form.issues[e.target.value + "_2"] = false;
      }
    },
    showCustomMessageBox: function(e) {
      e.target.parentElement.classList.add("active");
    },
    isStep: function(step) {
      if (step >= 5) {
        return this.cta_check && this.step === step;
      }
      return this.step === step;
    },
    nextStep() {
      this.forceRerender();
      const forme = document.getElementById("voice-component");
      this.errors = {};
      switch (this.step) {
        case 1:
          const feelings = this.form.feelings;
          let selected = [];

          this.addFeeling();

          for (const name in feelings) {
            if (feelings.hasOwnProperty(name)) {
              if (feelings[name]) {
                selected.push(name);
              }
            }
          }

          if (selected.length) {
            this.step = 2;
            return this.step;
          }

          this.errors.feelings = "Please select atleast one";
          forme.scrollIntoView();
          return this.step;

          break;
        case 2:
          if (
            !(
              this.form.issues.health_1 ||
              this.form.issues.health_2 ||
              this.form.issues.health_custom ||
              this.form.issues.qualityOfLiving_1 ||
              this.form.issues.qualityOfLiving_2 ||
              this.form.issues.qualityOfLiving_custom ||
              this.form.issues.future_1 ||
              this.form.issues.future_2 ||
              this.form.issues.future_custom
            )
          ) {
            this.errors.issues = "Please select atleast one checkbox";
            forme.scrollIntoView({
              behavior: "smooth",
              block: "center",
              inline: "nearest"
            });
            return this.errors;
          }

          const customIssue = v8n()
            .string()
            .maxLength(120);

          if (
            !v8n()
              .optional(customIssue)
              .test(this.form.issues.custom_message)
          ) {
            this.errors.issues = "Please enter max 120 characters";
            return this.errors;
          }
          break;
        case 3:
          if (
            !v8n()
              .string()
              .minLength(3)
              .test(this.form.first_name)
          ) {
            this.errors.first_name = "Please enter a valid first name";
          }

          if (
            !v8n()
              .optional(
                v8n()
                  .string()
                  .minLength(1)
              )
              .test(this.form.last_name)
          ) {
            this.errors.last_name = "Please enter a valid last name";
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
            !v8n()
              .string()
              .test(this.form.phone)
          ) {
            this.errors.phone = "Please enter a valid phone";
          }

          if (
            !v8n()
              .string()
              .minLength(3)
              .test(this.form.age)
          ) {
            this.errors.age =
              "You need to be 21 and over to sign this open letter";
          }

          if (!this.form.check_pdpc) {
            this.errors.check_pdpc =
              "Please accept privacy policy and terms and conditions";
          }

          if (!this.form.check_consent) {
            this.errors.check_consent = "Please check the concent";
          }

          if (Object.keys(this.errors).length) {
            const name = Object.keys(this.errors)[0] || "";

            if (name !== "") {
              let ele = document.getElementById(name);
              // .focus()
              ele.scrollIntoView({
                behavior: "smooth",
                block: "center",
                inline: "nearest"
              });
              this.$refs[name].focus();
            }

            return this.errors;
          }

          // var feelings = this._data.form.feelings.keys(obj).map(function(key) {
          //   return [Number(key), obj[key]];
          // });

          var data = {
            action: "getSignature",
            data: this._data,
            _nonce: this._data._nonce
          };
          this.loading = true;
          this.image_loading = true;
          var that = this;

          $.ajax({
            type: "POST",
            url: ajaxurl,
            data: data,
            success: function(results) {
              var data = $.parseJSON(results);
              if (data.errors) {
                that.errors.random = data.errors;
                that.loading = false;
                that.image_loading = false;
                return this.errors;
              }
              that.shareImage = data.image_url;
              that.shareUrl = data.share_url;
              that.signatureCount = data.signatureCount;
              that.loading = false;
              that.image_loading = false;

              forme.scrollIntoView({
                behavior: "smooth",
                block: "center",
                inline: "nearest"
              });
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
            that.errors.random = "Something went wrong";
            that.loading = false;
            that.image_loading = false;
          }, 30000);
          return this.errors;
          break;
      }
      forme.scrollIntoView();
      return (this.step =
        this.step < this.maxSteps ? this.step + 1 : this.step);
    },
    prevStep() {
      return (this.step = this.step > 1 ? this.step - 1 : this.step);
    },
    onUpdatePhone(payload) {
      this.form.phone_withcc = payload.formattedNumber;
      this.form.phonea = payload;
    }
  },
  watch: {
    "form.issues": {
      handler: function(newval, oldval) {
        const issues = this.form.issues;

        // depending on parent value set child value
        if (issues.health_1 && issues.health_2) {
          issues.health = true;
        } else {
          issues.health = false;
        }
        if (issues.future_1 && issues.future_2) {
          issues.future = true;
        } else {
          issues.future = false;
        }
        if (issues.qualityOfLiving_1 && issues.qualityOfLiving_2) {
          issues.qualityOfLiving = true;
        } else {
          issues.qualityOfLiving = false;
        }
      },
      deep: true
    }
  },
  computed: {
    feelin: function() {
      return this.form.feelings;
    }
  }
});
window.app = app;
