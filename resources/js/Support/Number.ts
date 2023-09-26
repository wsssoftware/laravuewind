

interface NumberFormatOptions {
    localeMatcher?: string | undefined;
    style?: string | undefined;
    currency?: string | undefined;
    currencySign?: string | undefined;
    useGrouping?: boolean | undefined;
    minimumIntegerDigits?: number | undefined;
    minimumFractionDigits?: number | undefined;
    maximumFractionDigits?: number | undefined;
    minimumSignificantDigits?: number | undefined;
    maximumSignificantDigits?: number | undefined;
}

export class NumberHelper {
    public static from(value: number|string): Numerable {
        return new Numerable(value);
    }
}

export class Numerable {

    public static locale: string;

    private readonly number: number;

    constructor(value: number|string) {
        if (typeof value === 'string') {
            this.number = Number(value);
        } else {
            this.number = value;
        }
    }

    public toPercentage(options: NumberFormatOptions = {}): string {
        return Intl.NumberFormat(Numerable.locale.replace('_', '-'), {
            style: 'percent',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
            ...options
        }).format(this.number);
    }
}