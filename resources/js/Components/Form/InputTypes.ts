import {Component} from "vue";

export type SelectChoice = {key: any, value: string|number|boolean, extra?: object};

export type SelectOptions = {
    clearable?: boolean,
    choices: SelectChoice[],
    multiple?: boolean,
    optionComponent?: Component,
    searchable?: boolean,
    searchPlaceholder?: string,
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
]

export default types;