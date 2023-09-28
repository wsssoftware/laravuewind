import {maskitoNumberOptionsGenerator} from '@maskito/kit';

export default function (min: number = 0, max: number = 100, precision: number = 2) {
    let options = {
        decimalZeroPadding: true,
        precision: precision,
        decimalSeparator: ',',
        thousandSeparator: '.',
        min: min,
        max:max,
        postfix: '%',
    }
    return maskitoNumberOptionsGenerator(options)
};