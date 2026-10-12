import { usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';

const colors = {
    success: { bg: '#16a34a', icon: '✓' },
    error: { bg: '#dc2626', icon: '✕' },
    warning: { bg: '#d97706', icon: '⚠' },
    info: { bg: '#2563eb', icon: 'ℹ' },
};

export default function FlashToast() {
    const { flash } = usePage().props;
    const [toasts, setToasts] = useState([]);

    useEffect(() => {
        const next = [];
        if (flash?.success) next.push({ type: 'success', message: flash.success });
        if (flash?.error) next.push({ type: 'error', message: flash.error });
        if (flash?.info) next.push({ type: 'info', message: flash.info });
        if (!next.length) {
            return undefined;
        }

        const stamped = next.map((toast, index) => ({ ...toast, id: Date.now() + index }));
        setToasts((current) => [...current, ...stamped]);

        const timer = setTimeout(() => {
            setToasts((current) => current.filter((toast) => !stamped.some((item) => item.id === toast.id)));
        }, 3500);

        return () => clearTimeout(timer);
    }, [flash?.success, flash?.error, flash?.info]);

    if (!toasts.length) {
        return null;
    }

    return (
        <div className="pointer-events-none fixed right-5 top-5 z-[99999] flex flex-col items-end gap-2.5">
            {toasts.map((toast) => {
                const style = colors[toast.type] || colors.info;
                return (
                    <div
                        key={toast.id}
                        className="pointer-events-auto flex min-w-[250px] max-w-[450px] items-center gap-2.5 rounded-[10px] px-5 py-3 text-[13.5px] font-semibold text-white shadow-lg"
                        style={{ background: style.bg }}
                    >
                        <span className="text-lg">{style.icon}</span>
                        <span>{toast.message}</span>
                    </div>
                );
            })}
        </div>
    );
}
