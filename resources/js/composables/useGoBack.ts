export function useGoBack() {
    const goBack = () => {
        if (typeof window !== 'undefined') {
            window.history.back();
        }
    };

    return { goBack };
}
