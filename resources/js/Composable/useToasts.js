import { reactive } from "vue";

// Module-level store: one toast stack shared app-wide.
const toasts = reactive([]);
let nextId = 1;

export const useToasts = () => {
    const dismiss = (id) => {
        const index = toasts.findIndex((toast) => toast.id === id);
        if (index !== -1) toasts.splice(index, 1);
    };

    const push = (type, message, timeout = 4500) => {
        const id = nextId++;
        toasts.push({ id, type, message });

        if (timeout) {
            setTimeout(() => dismiss(id), timeout);
        }
        return id;
    };

    return {
        toasts,
        push,
        dismiss,
        success: (message) => push("success", message),
        error: (message) => push("error", message, 7000),
    };
};
