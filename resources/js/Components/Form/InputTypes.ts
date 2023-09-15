
export type SelectChoice = {key: any, value: any};

export type SelectOptions = {
    clearable?: boolean,
    choices: SelectChoice[],
    multiple?: boolean,
    searchable?: boolean,
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