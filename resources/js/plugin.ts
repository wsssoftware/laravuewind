import {App} from "vue/dist/vue";
import languages, {translateStrings} from "./languages";
import {Numerable} from "./Support/Number";

type pluginOptions = {
    lang?: string,
    translateStrings?: translateStrings | object,
};

function getLanguageStrings(options: pluginOptions): translateStrings {
    if (options?.lang) {
        if (!Object.keys(languages).includes(options.lang)) {
            console.warn(`Language ${options.lang} is not supported`)
        } else {
            return languages[options.lang];
        }
    }

    return languages.en;
}

function mergeDeep(target: translateStrings, source: object): translateStrings {
    for (const key in source) {
        if (typeof source[key] === 'object') {
            if (!target[key]) {
                continue;
            }
            mergeDeep(target[key], source[key]);
        } else if (typeof target[key] === typeof source[key]) {
            Object.assign(target, {[key]: source[key]});
        }
    }
    return target;
}

let localApp: App;

function translate(key: string, defaultString?: string): string {
    let languageStrings = localApp.config.globalProperties.$lvw.languageStrings;
    return key.split('.')
        .reduce(
            (o, i) => {
                if (o) return o[i]
            },
            languageStrings
        ) ?? defaultString ?? undefined;
}


export default {
    install: (app: App, options?: pluginOptions): void => {
        const browserLanguage = navigator.language.replace('-', '_');
        if (!options?.lang) {
            options.lang = browserLanguage;
        }
        localApp = app;
        Numerable.locale = options?.lang ?? 'en';
        let languageStrings = getLanguageStrings(options);
        let userLanguageStrings = options?.translateStrings ?? {};
        languageStrings = mergeDeep(languageStrings, userLanguageStrings);

        app.config.globalProperties.$lvw = {
            languageStrings: languageStrings,
            translate: translate,
        };
    }
}