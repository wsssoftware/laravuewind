import {maskitoNumberOptionsGenerator} from '@maskito/kit';

export default function (min: number = 0, max: number = 1000000000, precision: number = 2) {
    let options = {
        decimalZeroPadding: true,
        precision: precision,
        decimalSeparator: ',',
        thousandSeparator: '.',
        min: min,
        max:max,
        prefix: 'R$',
    }
    return maskitoNumberOptionsGenerator(options)
};
