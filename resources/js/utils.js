export function getValue(obj, path) {
    if (!obj || !path) {
        return undefined;
    }

    return String(path)
        .split('.')
        .reduce((current, key) => (current == null ? undefined : current[key]), obj);
}

export function formatMoney(value) {
    const amount = Number(value ?? 0);
    return amount.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

export function formatDate(value) {
    if (!value) {
        return '—';
    }

    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return String(value);
    }

    return date.toLocaleDateString(undefined, {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
}

export function formatDateTime(value) {
    if (!value) {
        return { date: '—', time: '' };
    }

    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return { date: String(value), time: '' };
    }

    return {
        date: date.toLocaleDateString(undefined, {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        }),
        time: date.toLocaleTimeString(undefined, {
            hour: '2-digit',
            minute: '2-digit',
        }),
    };
}

export function paginatorData(items) {
    if (!items) {
        return [];
    }

    if (Array.isArray(items)) {
        return items;
    }

    return items.data ?? [];
}

export function toOptions(source) {
    if (!source) {
        return [];
    }

    if (Array.isArray(source)) {
        return source.map((entry) => {
            if (entry && typeof entry === 'object' && 'value' in entry) {
                return { value: String(entry.value), label: entry.label };
            }

            return { value: String(entry), label: String(entry) };
        });
    }

    return Object.entries(source).map(([value, label]) => ({
        value: String(value),
        label: String(label),
    }));
}

export function csrfToken() {
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
    return match ? decodeURIComponent(match[1]) : '';
}

export function can(user, permission) {
    return Boolean(user?.permissions?.includes(permission));
}

export function hasRole(user, role) {
    return Boolean(user?.roles?.includes(role));
}
