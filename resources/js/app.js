import "bootstrap";

// eMonitor Custom JavaScript

document.addEventListener("DOMContentLoaded", function () {
    // Password Toggle Functionality
    const passwordToggles = document.querySelectorAll("[data-password-toggle]");

    passwordToggles.forEach((toggle) => {
        toggle.addEventListener("click", function () {
            const passwordInput = this.closest(".input-group").querySelector(
                'input[type="password"]',
            );
            const icon = this.querySelector("i");
            const text = this.querySelector(".password-toggle-text");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
                text.textContent = "Hide";
            } else {
                passwordInput.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
                text.textContent = "Show";
            }
        });
    });

    // Navbar Scroll Effect
    const navbar = document.querySelector(".navbar");
    if (navbar) {
        window.addEventListener("scroll", () => {
            if (window.scrollY > 50) {
                navbar.style.background = "rgba(13, 110, 253, 0.95)";
                navbar.style.backdropFilter = "blur(10px)";
            } else {
                navbar.style.background = "rgba(13, 110, 253, 1)";
                navbar.style.backdropFilter = "none";
            }
        });
    }

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach((alert) => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Table row animations
    const tableRows = document.querySelectorAll(".account-table tbody tr");
    tableRows.forEach((row, index) => {
        row.style.opacity = "0";
        row.style.transform = "translateY(20px)";
        setTimeout(() => {
            row.style.transition = "all 0.6s ease";
            row.style.opacity = "1";
            row.style.transform = "translateY(0)";
        }, index * 100);
    });

    // Card hover scale effect
    const cards = document.querySelectorAll(".card, .hero-card");
    cards.forEach((card) => {
        card.addEventListener("mouseenter", function () {
            this.style.transform = "translateY(-8px) scale(1.02)";
        });
        card.addEventListener("mouseleave", function () {
            this.style.transform = "translateY(0) scale(1)";
        });
    });

    // Form validation styling
    const forms = document.querySelectorAll("form");
    forms.forEach((form) => {
        form.addEventListener("submit", function () {
            const inputs = this.querySelectorAll(
                "input[required], select[required]",
            );
            let isValid = true;

            inputs.forEach((input) => {
                if (!input.value.trim()) {
                    input.classList.add("is-invalid");
                    isValid = false;
                } else {
                    input.classList.remove("is-invalid");
                    input.classList.add("is-valid");
                }
            });

            return isValid;
        });
    });

    // Responsive table on mobile
    function makeTableResponsive() {
        const tables = document.querySelectorAll(".account-table");
        tables.forEach((table) => {
            if (window.innerWidth < 768) {
                // Add mobile-friendly styles
                table.classList.add("table-responsive-sm");
            } else {
                table.classList.remove("table-responsive-sm");
            }
        });
    }

    makeTableResponsive();
    window.addEventListener("resize", makeTableResponsive);
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute("href"));
        if (target) {
            target.scrollIntoView({
                behavior: "smooth",
                block: "start",
            });
        }
    });
});
