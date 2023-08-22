export function getDate(): string {
    let dateObj = new Date();
    let month = getOnlyMonth(dateObj.getUTCMonth());
    let day = dateObj.getUTCDate();
    let year = dateObj.getUTCFullYear();

    return month + ' ' + day + ', ' + year;
}

export function getMonth(): string {
    let dateObj = new Date();
    let month = getOnlyMonth(dateObj.getUTCMonth());
    let year = dateObj.getUTCFullYear();

    return month + ', ' + year;
}

function getOnlyMonth(month: number): string {
    const months = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];

    return months[month];
}
