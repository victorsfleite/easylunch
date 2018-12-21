export default function(value) {
    if (!value && value !== 0) {
        return '';
    }

    return (
        value
            .toString()
            .charAt(0)
            .toUpperCase() + value.slice(1)
    );
}
