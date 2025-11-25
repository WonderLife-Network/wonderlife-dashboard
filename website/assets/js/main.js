/* ---------------------------------------------------------
   WONDERLIFE NETWORK – UI SCRIPTS
   Neon FX • Glow • Animations • Lightbox • Mobile Menu
   --------------------------------------------------------- */

document.addEventListener("DOMContentLoaded", () => {

    console.log("WonderLife Website JavaScript Loaded.");

    // -----------------------------------------------------
    // MOBILE MENU
    // -----------------------------------------------------
    const header = document.querySelector(".wl-header");
    if (header) {
        const nav = header.querySelector("nav");
        const burger = document.createElement("div");
        burger.classList.add("burger");
        burger.innerHTML = "☰";

        header.appendChild(burger);

        burger.addEventListener("click", () => {
            nav.classList.toggle("nav-open");
            burger.classList.toggle("active");
        });
    }

    // -----------------------------------------------------
    // STICKY HEADER
    // -----------------------------------------------------
    let lastScroll = 0;
    const headerEl = document.querySelector(".wl-header");

    window.addEventListener("scroll", () => {
        let currentScroll = window.pageYOffset;

        if (currentScroll > lastScroll) {
            headerEl.style.opacity = "0.6";
        } else {
            headerEl.style.opacity = "1";
        }

        lastScroll = currentScroll;
    });

    // -----------------------------------------------------
    // FADE-IN ON SCROLL
    // -----------------------------------------------------
    const faders = document.querySelectorAll(".card, .news-item, .team-card, .creator-card, .gallery-img");

    const fadeObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("fade-in");
            }
        });
    }, { threshold: 0.2 });

    faders.forEach(f => fadeObserver.observe(f));

    // -----------------------------------------------------
    // BUTTON RIPPLE EFFECT
    // -----------------------------------------------------
    document.querySelectorAll(".btn-glow")
        .forEach(btn => {
            btn.addEventListener("click", e => {
                let circle = document.createElement("span");
                circle.classList.add("ripple");

                let x = e.clientX - e.target.offsetLeft;
                let y = e.clientY - e.target.offsetTop;

                circle.style.left = `${x}px`;
                circle.style.top = `${y}px`;

                btn.appendChild(circle);

                setTimeout(() => {
                    circle.remove();
                }, 600);
            });
        });

    // -----------------------------------------------------
    // LIGHTBOX (Galerie)
    // -----------------------------------------------------
    const galleryImages = document.querySelectorAll(".gallery-img");
    let lightbox, imgElement;

    if (galleryImages.length > 0) {
        lightbox = document.createElement("div");
        lightbox.classList.add("lightbox");
        lightbox.innerHTML = `<img class='lightbox-img'><span class='close'>×</span>`;
        document.body.appendChild(lightbox);

        imgElement = lightbox.querySelector(".lightbox-img");
        const close = lightbox.querySelector(".close");

        galleryImages.forEach(img => {
            img.addEventListener("click", () => {
                imgElement.src = img.src;
                lightbox.classList.add("active");
            });
        });

        close.addEventListener("click", () => {
            lightbox.classList.remove("active");
        });

        lightbox.addEventListener("click", e => {
            if (e.target === lightbox) lightbox.classList.remove("active");
        });
    }

    // -----------------------------------------------------
    // NEON GLOW FX (Hover-Pulse)
    // -----------------------------------------------------
    const glowItems = document.querySelectorAll(".card, .team-card, .creator-card, .status-grid .card");

    glowItems.forEach(item => {
        item.addEventListener("mouseenter", () => {
            item.style.boxShadow = "0 0 25px rgba(255,0,212,0.8)";
            item.style.transform = "translateY(-5px)";
        });

        item.addEventListener("mouseleave", () => {
            item.style.boxShadow = "";
            item.style.transform = "";
        });
    });

});
