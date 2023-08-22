export function getError(objErrors: any, error: string): string {
    const err = objErrors![error];

    if (err && err.length > 0) {
        return err[0];
    }

    return '';
}
