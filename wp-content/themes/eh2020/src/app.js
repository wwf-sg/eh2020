window.Vue = require("vue");
import v8n from "v8n";
import axios from "axios";
import $ from "jquery";

// import vSelect from "vue-select";
// import "vue-select/dist/vue-select.css";
// Vue.component("v-select", vSelect);

// import TelInput from "vue-tel-input";
// import "vue-tel-input/dist/vue-tel-input.css";
// Vue.component("vue-tel-input", TelInput);

// require("bootstrap");

$(document).ready(function() {
  setInterval(function time() {
    var d = new Date();
    var target = new Date("March 28, 2020 17:30:00");
    var diffTime = Math.abs(target - d);
    var days = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    var hours = ("0" + (23 - d.getHours())).slice(-2);
    var min = ("0" + (59 - d.getMinutes())).slice(-2);
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
var app = new Vue({
  el: "#contente",
  template: "#voice-template",
  components: {},
  data: function() {
    return {
      _nonce: nonce,
      loading: false,
      image_loading: false,
      signatureCount: 0,
      shareImage: "",
      shareUrl: "https://earthhour.sg/",
      step: 3,
      cta_check: false,
      maxSteps: 4,
      form: {
        first_name: "Manoj",
        last_name: "hl",
        name: "",
        email: "123@gmail.com",
        phone: "123",
        age: 22,
        country: "SG",
        check_pdpc: true,
        issues: {
          health: false,
          economy: true,
          standardOfLiving: false,
          custom_message: ""
        }
      },
      errors: {}
    };
  },
  mounted: function() {},
  methods: {
    isStep: function(step) {
      if (step >= 5) {
        return this.cta_check && this.step === step;
      }
      return this.step === step;
    },
    nextStep() {
      const forme = document.getElementById("voice-component");
      this.errors = {};
      switch (this.step) {
        case 1:
          this.step = 2;
          forme.scrollIntoView();
          return this.step;
          break;
        case 2:
          if (
            !(
              this.form.issues.economy ||
              this.form.issues.health ||
              this.form.issues.standardOfLiving ||
              this.form.issues.custom_message
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
              .optional(
                v8n()
                  .number()
                  .greaterThan(20)
              )
              .test(parseInt(this.form.age))
          ) {
            this.errors.age =
              "You need to be 21 and over to sign this open letter";
          }

          if (!this.form.check_pdpc) {
            this.errors.check_pdpc =
              "Please accept privacy policy and terms and conditions";
          }

          if (Object.keys(this.errors).length) {
            const name = Object.keys(this.errors)[0] || "";

            if (name !== "") {
              console.log(name);

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
    }
  },
  mounted() {
    // console.log(this.);
  },
  computed: {}
});
window.app = app;
