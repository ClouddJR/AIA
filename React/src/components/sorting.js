export function sortByTitleAscending(b1, b2) {
    if (b1.title < b2.title) {
        return -1;
    }
    if (b1.title > b2.title) {
        return 1;
    }
    return 0;
}

export function sortByTitleDescending(b1, b2) {
    if (b1.title > b2.title) {
        return -1;
    }
    if (b1.title < b2.title) {
        return 1;
    }
    return 0;
}

export function sortByAuthorAscending(b1, b2) {
    if (b1.author < b2.author) {
        return -1;
    }
    if (b1.author > b2.author) {
        return 1;
    }
    return 0;
}

export function sortByAuthorDescending(b1, b2) {
    if (b1.author > b2.author) {
        return -1;
    }
    if (b1.author < b2.author) {
        return 1;
    }
    return 0;
}

export function sortByRatingAscending(b1, b2) {
    if (parseInt(b1.rating) < parseInt(b2.rating)) {
        return -1;
    }
    if (parseInt(b1.rating) > parseInt(b2.rating)) {
        return 1;
    }
    return 0;
}

export function sortByRatingDescending(b1, b2) {
    if (parseInt(b1.rating) > parseInt(b2.rating)) {
        return -1;
    }
    if (parseInt(b1.rating) < parseInt(b2.rating)) {
        return 1;
    }
    return 0;
}