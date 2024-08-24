/**
 * This will track all the images and fonts for publishing.
 */
import.meta.glob(["../images/**", "../fonts/**"]);

/**
 * Main vue bundler.
 */
import { createApp } from "vue/dist/vue.esm-bundler";

/**
 * We are defining all the global rules here and configuring
 * all the `vee-validate` settings.
 */
import { configure, defineRule, Field, Form, ErrorMessage } from "vee-validate";
import { localize } from "@vee-validate/i18n";
import en from "@vee-validate/i18n/dist/locale/en.json";
import * as AllRules from '@vee-validate/rules';

/**
 * Registration of all global validators.
 */
Object.keys(AllRules).forEach(rule => {
    defineRule(rule, AllRules[rule]);
});

/**
 * This regular expression allows phone numbers with the following conditions:
 * - The phone number can start with an optional "+" sign.
 * - After the "+" sign, there should be one or more digits.
 *
 * This validation is sufficient for global-level phone number validation. If
 * someone wants to customize it, they can override this rule.
 */
defineRule("phone", (value) => {
    if (!value || !value.length) {
        return true;
    }

    if (!/^\+?\d+$/.test(value)) {
        return false;
    }

    return true;
});

defineRule("decimal", (value, { decimals = '*', separator = '.' } = {}) => {
    if (value === null || value === undefined || value === '') {
        return true;
    }

    if (Number(decimals) === 0) {
        return /^-?\d*$/.test(value);
    }

    const regexPart = decimals === '*' ? '+' : `{1,${decimals}}`;
    const regex = new RegExp(`^[-+]?\\d*(\\${separator}\\d${regexPart})?([eE]{1}[-]?\\d+)?$`);

    return regex.test(value);
});

defineRule("required_if", (value, { condition = true } = {}) => {
    if (condition) {
        if (value === null || value === undefined || value === '') {
            return false;
        }
    }

    return true;
});

configure({
    /**
     * Built-in error messages and custom error messages are available. Multiple
     * locales can be added in the same way.
     */
    generateMessage: localize({
        en: {
            ...en,
            messages: {
                ...en.messages,
                phone: "This {field} must be a valid phone number",
            },
        },
    }),

    validateOnBlur: true,
    validateOnInput: true,
    validateOnChange: true,
});

/**
 * Main root application registry.
 */
window.app = createApp({
    data() {
        return {};
    },

    mounted() {
        var lazyImages = [].slice.call(document.querySelectorAll('img.lazy'));

        let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    let lazyImage = entry.target;

                    lazyImage.src = lazyImage.dataset.src;
                    
                    lazyImage.classList.remove("lazy");

                    lazyImageObserver.unobserve(lazyImage);
                }
            });
        });

        lazyImages.forEach(function(lazyImage) {
            lazyImageObserver.observe(lazyImage);
        });
    },

    methods: {
        onSubmit() {},

        onInvalidSubmit() {},
    },
});

/**
 * Global plugins registration.
 */
import Axios from "./plugins/axios";
import Emitter from "./plugins/emitter";
import Shop from "./plugins/shop";
import createElement from "./plugins/createElement";

[Axios, Emitter, Shop, createElement].forEach((plugin) => app.use(plugin));

import Flatpickr from "flatpickr";
import 'flatpickr/dist/flatpickr.css';
window.Flatpickr = Flatpickr;

import Draggable from 'vuedraggable';

/**
 * Global components registration;
 */
app.component("VForm", Form);
app.component("VField", Field);
app.component("VErrorMessage", ErrorMessage);
app.component("draggable", Draggable);

/**
 * Global directives.
 */
import SlugifyTarget from "./directives/slugify-target";
import Debounce from "./directives/debounce";
import Code from "./directives/code";

app.directive("slugify-target", SlugifyTarget);
app.directive("debounce", Debounce);
app.directive("code", Code);

/**
 * Load event, the purpose of using the event is to mount the application
 * after all of our `Vue` components which is present in blade file have
 * been registered in the app. No matter what `app.mount()` should be
 * called in the last.
 */
window.addEventListener("load", function (event) {
    app.mount("#app");
});
