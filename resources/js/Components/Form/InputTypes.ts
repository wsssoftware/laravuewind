

export const fillable: string[] = [
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
    ...fillable,
    'select',
]

export default types;