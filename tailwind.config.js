module.exports = {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
        "./resources/css/**/*.css",
    ],
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "surface-dim": "#d0dbed",
                "surface-bright": "#f8f9ff",
                "primary-container": "#10b981",
                "surface-variant": "#d9e3f6",
                "secondary-fixed-dim": "#f9bd22",
                "surface-container": "#e6eeff",
                "secondary-container": "#ffc329",
                "surface-container-lowest": "#ffffff",
                "surface-container-low": "#eff4ff",
                primary: "#006c49",
                "on-surface": "#121c2a",
                tertiary: "#2b6954",
                background: "#f8f9ff",
                outline: "#6c7a71",
            },
            fontFamily: {
                headline: ["Manrope", "ui-sans-serif", "system-ui"],
                body: ["Inter", "ui-sans-serif", "system-ui"],
                label: ["Inter", "ui-sans-serif", "system-ui"],
            },
            borderRadius: {
                DEFAULT: "1rem",
                lg: "2rem",
                xl: "3rem",
                full: "9999px",
            },
        },
    },
    plugins: [require("@tailwindcss/forms")],
};
