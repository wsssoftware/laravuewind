import {MaskitoOptions} from "@maskito/core";

type options = {
    allowCellphone?: boolean,
    allowLocalFare?: boolean,
    allowNonRegional?: boolean,
    allowPhone?: boolean
    allowPublicServices?: boolean
};

export default function (options? : options): MaskitoOptions {
    return {
        mask: elementState => {
            options = {
                allowCellphone: options?.allowCellphone ?? true,
                allowLocalFare: options?.allowLocalFare ?? true,
                allowNonRegional: options?.allowNonRegional ?? true,
                allowPhone: options?.allowPhone ?? true,
                allowPublicServices: options?.allowPublicServices ?? true,
            };

            if (options.allowLocalFare && /^400$/.test(elementState.value.substring(0, 3))) {
                return ['4', '0', '0', /\d/, '-', /\d/, /\d/, /\d/, /\d/];
            }
            if (options.allowNonRegional && /^0([3589])$/.test(elementState.value.substring(0, 2))) {
                return ['0', /\d/, '0', '0', ' ', /\d/, /\d/, /\d/, ' ', /\d/, /\d/, /\d/, /\d/];
            }
            if (options.allowPublicServices && /^1[0-9]{2}$/.test(elementState.value)) {
                return [/\d/, /\d/, /\d/];
            }
            if ((options.allowCellphone || options.allowPhone) && /^[1-9][0-9]$/.test(elementState.value.substring(0, 2))) {
                if (options.allowCellphone && /^[1-9][0-9] 9$/.test(elementState.value.substring(0, 4))) {
                    return [/[1-9]/, /[0-9]/, ' ', /9/, ' ', /[5-9]/, /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/];
                }
                if (options.allowPhone && /^[1-9][0-9] [1-5]$/.test(elementState.value.substring(0, 4))) {
                    return [/[1-9]/, /[0-9]/, ' ', /[1-5]/, /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/];
                }

                return [/[1-9]/, /[0-9]/, ' ', /\d/];
            }

            return /^\d{0,3}$/;
        },
    };
};