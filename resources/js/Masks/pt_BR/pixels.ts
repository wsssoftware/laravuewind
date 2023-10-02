import {maskitoNumberOptionsGenerator} from '@maskito/kit';

export default function (min?: number, max?: number) {
    let options = {
        decimalZeroPadding: true,
        precision: 0,
        decimalSeparator: ',',
        thousandSeparator: '.',
        min: min ?? undefined,
        max: max ?? undefined,
        postfix: 'px',
    };
    return maskitoNumberOptionsGenerator(options);
};