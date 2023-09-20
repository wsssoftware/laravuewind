import {Component} from "vue";
import {FilePondOptions} from "filepond";

export type SelectChoice = {key: any, value: string|number|boolean, extra?: object};

export type SelectOptions = {
    clearable?: boolean,
    choices: SelectChoice[],
    multiple?: boolean,
    optionComponent?: Component,
    searchable?: boolean,
};

export type FilePondParams = {
    options?: FilePondOptions,
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