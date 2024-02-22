import {Component} from "vue";
import {FilePondOptions} from "filepond";


export type SelectChoice = {key: string, value: string|number|boolean, extra?: object}|{code: string, name: string|number|boolean, extra?: object};

export type SelectOptions = {
    clearable?: boolean,
    choices: SelectChoice[]|string|null,
    multiple?: boolean,
    optionComponent?: Component,
    searchable?: boolean,
};

export type FilePondParams = {
    options?: FilePondOptions&{
        allowImageValidateSize?: boolean;
        imageValidateSizeMinWidth?: number;
        imageValidateSizeMaxWidth?: number;
        imageValidateSizeMinHeight?: number;
        imageValidateSizeMaxHeight?: number;
        imageValidateSizeLabelFormatError?: string;
        imageValidateSizeLabelImageSizeTooSmall?: string;
        imageValidateSizeLabelImageSizeTooBig?: string;
        imageValidateSizeLabelExpectedMinSize?: string;
        imageValidateSizeLabelExpectedMaxSize?: string;
        imageValidateSizeMinResolution?: number;
        imageValidateSizeMaxResolution?: number;
        imageValidateSizeLabelImageResolutionTooLow?: string;
        imageValidateSizeLabelImageResolutionTooHigh?: string;
        imageValidateSizeLabelExpectedMinResolution?: string;
        imageValidateSizeLabelExpectedMaxResolution?: string;
        imageValidateSizeMeasure?: (
            file: File
        ) => Promise<{ width: number; height: number }>;
    }&{
        allowImagePreview?: boolean;
        imagePreviewMinHeight?: number;
        imagePreviewMaxHeight?: number;
        imagePreviewHeight?: number;
        imagePreviewMaxFileSize?: string;
        imagePreviewTransparencyIndicator?: string;
        imagePreviewMaxInstantPreviewFileSize?: number;
        imagePreviewMarkupShow?: boolean;
        imagePreviewMarkupFilter?: (markupItem: any) => true;
    }&{
        allowFileTypeValidation?: boolean;
        acceptedFileTypes?: string[];
        labelFileTypeNotAllowed?: string;
        fileValidateTypeLabelExpectedTypes?: string;
        fileValidateTypeLabelExpectedTypesMap?: object;
        fileValidateTypeDetectType?: (file: globalThis.File, type: string) => Promise<string>;
    }&{
        allowFileSizeValidation?: boolean;
        minFileSize?: string | null;
        labelMinFileSizeExceeded?: string;
        labelMinFileSize?: string;
        maxFileSize?: string | null;
        maxTotalFileSize?: string | null;
        labelMaxFileSizeExceeded?: string;
        labelMaxFileSize?: string;
        labelMaxTotalFileSizeExceeded?: string;
        labelMaxTotalFileSize?: string;
    },
    plugins?: any[],
};

export const Fillable: string[] = [
    'color',
    'date',
    'datetime',
    'datetime-local',
    'email',
    'file',
    'image',
    'month',
    'number',
    'password',
    'search',
    'tel',
    'text',
    'textarea',
    'time',
    'url',
    'week',
];

const types: string[] = [
    ...Fillable,
    'select',
    'filepond'
]

export default types;
