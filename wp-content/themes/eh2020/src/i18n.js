import Vue from "vue";
import VueI18n from "vue-i18n";

Vue.use(VueI18n);

const messages = {
  en: {
    message: {
      step1: {
        line1: `Open Letter to Singapore`
      }
    }
  },
  cn: {
    message: {
      step1: {
        line1: `地球紧急公开信`
      }
    }
  },
  ml: {
    message: {
      step1: {
        line1: `Surat Terbuka Kepada Singapura`
      }
    }
  },
  tn: {
    message: {
      step1: {
        line1: `சிங்கப்பூருக்கு திறந்த கடிதம்`
      }
    }
  }
};

export const i18n = new VueI18n({
  locale: "en", // set locale
  fallbackLocale: "se", // set fallback locale
  messages // set locale messages
});
