import './styles/app.css';

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById("sidebar");
    const burger = document.getElementById("burger");
    const companyTopLeft = document.getElementById("companyTopLeft");
    const companyNextBurger = document.getElementById("companyNextBurger");
    const main = document.getElementById("main");
    const topbarInner = document.getElementById("topbarInner");
    const overlay = document.getElementById("overlay");
    const userBtn = document.getElementById("userBtn");
    const userDropdown = document.getElementById("userDropdown");
    const userChevron = document.getElementById("userChevron");

    let sidebarOpenDesktop = true;

    function applyCompanyVisibility() {
        if (window.innerWidth < 1024) {
            companyTopLeft?.classList.add("hidden");
            companyNextBurger?.classList.remove("hidden");
        } else {
            companyTopLeft?.classList.remove("hidden");
            if (sidebarOpenDesktop) {
                companyNextBurger?.classList.add("hidden");
                companyTopLeft?.classList.remove("-translate-x-full");
            } else {
                companyNextBurger?.classList.remove("hidden");
                companyTopLeft?.classList.add("-translate-x-full");
            }
        }
    }

    function initLayout() {
        if (window.innerWidth >= 1024) {
            sidebar?.classList.remove("-translate-x-full");
            main?.classList.add("lg:ml-64");
            topbarInner?.classList.add("lg:ml-64");
            sidebarOpenDesktop = true;
        } else {
            sidebar?.classList.add("-translate-x-full");
            main?.classList.remove("lg:ml-64");
            topbarInner?.classList.remove("lg:ml-64");
            sidebarOpenDesktop = false;
            overlay?.classList.add("hidden");
        }
        applyCompanyVisibility();
    }

    burger?.addEventListener("click", () => {
        if (window.innerWidth < 1024) {
            const isHidden = sidebar?.classList.contains("-translate-x-full");
            if (isHidden) {
                sidebar?.classList.remove("-translate-x-full");
                overlay?.classList.remove("hidden");
            } else {
                sidebar?.classList.add("-translate-x-full");
                overlay?.classList.add("hidden");
            }
        } else {
            sidebarOpenDesktop = !sidebarOpenDesktop;
            sidebar?.classList.toggle("-translate-x-full");
            companyTopLeft?.classList.toggle("-translate-x-full");
            main?.classList.toggle("lg:ml-64");
            topbarInner?.classList.toggle("lg:ml-64");
            applyCompanyVisibility();
        }
    });

    overlay?.addEventListener("click", () => {
        sidebar?.classList.add("-translate-x-full");
        overlay?.classList.add("hidden");
    });

    userBtn?.addEventListener("click", (e) => {
        e.stopPropagation();
        userDropdown?.classList.toggle("hidden");
        userChevron?.classList.toggle("rotate-180");
    });

    document.addEventListener("click", () => {
        userDropdown?.classList.add("hidden");
        userChevron?.classList.remove("rotate-180");
    });

    window.addEventListener("resize", initLayout);
    initLayout(); // Initial run
});