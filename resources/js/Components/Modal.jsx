import { useEffect } from 'react';

export default function Modal({ show = false, onClose, title, children, maxWidth = 'max-w-lg' }) {
    useEffect(() => {
        if (!show) {
            return undefined;
        }

        const onKey = (event) => {
            if (event.key === 'Escape') {
                onClose?.();
            }
        };

        document.addEventListener('keydown', onKey);
        return () => document.removeEventListener('keydown', onKey);
    }, [show, onClose]);

    if (!show) {
        return null;
    }

    return (
        <div
            className="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 p-5"
            onClick={onClose}
        >
            <div
                className={`w-full overflow-hidden rounded-[14px] bg-white shadow-2xl ${maxWidth}`}
                onClick={(event) => event.stopPropagation()}
            >
                {title && (
                    <div className="flex items-center justify-between border-b border-[#f0f0f0] px-6 py-5">
                        <h2 className="text-lg font-bold text-[#1a1a1a]">{title}</h2>
                        <button
                            type="button"
                            onClick={onClose}
                            className="flex h-7 w-7 items-center justify-center rounded-full bg-red-600 text-white"
                        >
                            ×
                        </button>
                    </div>
                )}
                {children}
            </div>
        </div>
    );
}
