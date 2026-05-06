<?php
// Shared Tailwind config + head assets for VeVit Store
// Usage: include in <head>
?>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&family=Space+Grotesk:wght@500&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= defined('VEVIT_BASE') ? VEVIT_BASE : '' ?>assets/css/style.css">
<script id="tailwind-config">
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        "surface": "#131313",
        "surface-dim": "#131313",
        "surface-bright": "#3a3939",
        "surface-container-lowest": "#0e0e0e",
        "surface-container-low": "#1c1b1b",
        "surface-container": "#201f1f",
        "surface-container-high": "#2a2a2a",
        "surface-container-highest": "#353534",
        "surface-variant": "#353534",
        "on-surface": "#e5e2e1",
        "on-surface-variant": "#bbcabf",
        "outline": "#86948a",
        "outline-variant": "#3c4a42",
        "primary": "#4edea3",
        "on-primary": "#003824",
        "primary-container": "#10b981",
        "on-primary-container": "#00422b",
        "primary-fixed": "#6ffbbe",
        "primary-fixed-dim": "#4edea3",
        "on-primary-fixed": "#002113",
        "on-primary-fixed-variant": "#005236",
        "secondary": "#bdc7d9",
        "on-secondary": "#27313f",
        "secondary-container": "#404a59",
        "on-secondary-container": "#afb9cb",
        "tertiary": "#c6c6c7",
        "on-tertiary": "#2f3131",
        "tertiary-container": "#a2a3a3",
        "error": "#ffb4ab",
        "on-error": "#690005",
        "error-container": "#93000a",
        "background": "#131313",
        "on-background": "#e5e2e1"
      },
      borderRadius: {
        DEFAULT: "0.125rem",
        lg: "0.25rem",
        xl: "0.5rem",
        full: "0.75rem"
      },
      spacing: {
        xs: "4px",
        sm: "12px",
        base: "8px",
        md: "24px",
        lg: "48px",
        xl: "80px",
        margin: "32px",
        gutter: "24px"
      },
      fontFamily: {
        "display": ["Plus Jakarta Sans"],
        "h1": ["Plus Jakarta Sans"],
        "h2": ["Plus Jakarta Sans"],
        "body-lg": ["Plus Jakarta Sans"],
        "body-md": ["Plus Jakarta Sans"],
        "caption": ["Plus Jakarta Sans"],
        "mono-label": ["Space Grotesk"]
      },
      fontSize: {
        "display": ["48px", { lineHeight: "1.1", letterSpacing: "-0.02em", fontWeight: "800" }],
        "h1": ["32px", { lineHeight: "1.2", fontWeight: "700" }],
        "h2": ["24px", { lineHeight: "1.3", fontWeight: "700" }],
        "body-lg": ["18px", { lineHeight: "1.6", fontWeight: "400" }],
        "body-md": ["16px", { lineHeight: "1.6", fontWeight: "400" }],
        "mono-label": ["14px", { lineHeight: "1.0", letterSpacing: "0.05em", fontWeight: "500" }],
        "caption": ["12px", { lineHeight: "1.4", fontWeight: "500" }]
      }
    }
  }
};
</script>
