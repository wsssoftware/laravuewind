import {maskitoNumberOptionsGenerator} from '@maskito/kit';

export default function (min?: number, max?: number, precision: number = 0) {
    let options = {
        decimalZeroPadding: true,
        precision: precision,
        decimalSeparator: ',',
        thousandSeparator: '.',
        min: min ?? undefined,
        max: max ?? undefined,
        postfix: 'gr',
    };
    return maskitoNumberOptionsGenerator(options);
};