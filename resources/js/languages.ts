export type translateStrings = {
    form: {
        select: {
            searchPlaceholder: string,
            searchNotFound: string,
        }
    },
};

const en: translateStrings = {
    form: {
        select: {
            searchPlaceholder: 'What are you looking for?',
            searchNotFound: 'No results found',
        }
    }
};

const pt_BR: translateStrings = {
    form: {
        select: {
            searchPlaceholder: 'O que você está procurando?',
            searchNotFound: 'Nenhum resultado encontrado',
        }
    }
};

export default {
    en,
    pt_BR,
};
