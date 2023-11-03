export function percentageToForm(value: number|null, fallback: number|null = null): string {
    if (value === null || value === undefined) {
        if (fallback === null || fallback === undefined) {
            return null;
        }
        value = fallback;
    }
    let newValue = ''+(value * 100);
    return newValue.replace('.', ',');
}
