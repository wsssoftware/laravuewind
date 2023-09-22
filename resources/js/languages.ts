export type translateStrings = {
    filePond: {
        labelIdle: string,
        labelInvalidField: string,
        labelFileWaitingForSize: string,
        labelFileSizeNotAvailable: string,
        labelFileLoading: string,
        labelFileLoadError: string,
        labelFileProcessing: string,
        labelFileProcessingComplete: string,
        labelFileProcessingAborted: string,
        labelFileProcessingError: string,
        labelFileProcessingRevertError: string,
        labelFileRemoveError: string,
        labelTapToCancel: string,
        labelTapToRetry: string,
        labelTapToUndo: string,
        labelButtonRemoveItem: string,
        labelButtonAbortItemLoad: string,
        labelButtonRetryItemLoad: string,
        labelButtonAbortItemProcessing: string,
        labelButtonUndoItemProcessing: string,
        labelButtonRetryItemProcessing: string,
        labelButtonProcessItem: string,
        labelMaxFileSizeExceeded: string,
        labelMaxFileSize: string,
        labelMaxTotalFileSizeExceeded: string,
        labelMaxTotalFileSize: string,
        labelFileTypeNotAllowed: string,
        fileValidateTypeLabelExpectedTypes: string,
        imageValidateSizeLabelFormatError: string,
        imageValidateSizeLabelImageSizeTooSmall: string,
        imageValidateSizeLabelImageSizeTooBig: string,
        imageValidateSizeLabelExpectedMinSize: string,
        imageValidateSizeLabelExpectedMaxSize: string,
        imageValidateSizeLabelImageResolutionTooLow: string,
        imageValidateSizeLabelImageResolutionTooHigh: string,
        imageValidateSizeLabelExpectedMinResolution: string,
        imageValidateSizeLabelExpectedMaxResolution: string,
    },
    form: {
        select: {
            noChoices: string,
            searchPlaceholder: string,
            searchNotFound: string,
        }
    },
};

import filePondEn from 'filepond/locale/en-en';
import filePondPtBR from 'filepond/locale/pt-br';

const en: translateStrings = {
    filePond: filePondEn,
    form: {
        select: {
            noChoices: 'No items available',
            searchPlaceholder: 'What are you looking for?',
            searchNotFound: 'No results found',
        }
    }
};

const pt_BR: translateStrings = {
    filePond: filePondPtBR,
    form: {
        select: {
            noChoices: 'Nenhum item disponível',
            searchPlaceholder: 'O que você está procurando?',
            searchNotFound: 'Nenhum resultado encontrado',
        }
    },
};

export default {
    en,
    pt_BR,
};
