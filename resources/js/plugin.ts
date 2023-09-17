import {App} from "vue/dist/vue";
import {translateStrings} from "./languages";
import languages from "./languages";

type pluginOptions = {
    lang?: string,
    translateStrings?: translateStrings | object,
};

function getLanguageStrings(options: pluginOptions): translateStrings {
    if (options?.lang) {
        if (!Object.keys(languages).includes(options.lang)) {
            throw new Error(`Language ${options.lang} is not supported`);
        }
        return languages[options.lang];
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
        localApp = app;
        let languageStrings = getLanguageStrings(options);
        let userLanguageStrings = options?.translateStrings ?? {};
        languageStrings = mergeDeep(languageStrings, userLanguageStrings);

        app.config.globalProperties.$lvw = {
            languageStrings: languageStrings,
            translate: translate,
        };
    }
}