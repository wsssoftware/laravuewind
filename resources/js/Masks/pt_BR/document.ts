import {MaskitoOptions} from "@maskito/core";

type options = {
    allowCNPJ?: boolean,
    allowCPF?: boolean,
};

export default function (options? : options): MaskitoOptions {
    return {
        mask: elementState => {
            options = {
                allowCNPJ: options?.allowCNPJ ?? true,
                allowCPF: options?.allowCPF ?? true,
            };

            let cpfMask: (RegExp|string)[] = [/\d/, /\d/, /\d/, '.', /\d/, /\d/, /\d/, '.', /\d/, /\d/, /\d/, '-', /\d/, /\d/];
            let cnpjMask: (RegExp|string)[] = [/\d/, /\d/, '.', /\d/, /\d/, /\d/, '.', /\d/, /\d/, /\d/, '/', /\d/, /\d/, /\d/, /\d/, '-', /\d/, /\d/];

            if (options.allowCNPJ && !options.allowCPF) {
                return cnpjMask;
            } else if (options.allowCPF && !options.allowCNPJ) {
                return cpfMask;
            } else if (elementState.value.length > 14) {
                return cnpjMask;
            } else {
                return cpfMask;
            }
        },
    };
};