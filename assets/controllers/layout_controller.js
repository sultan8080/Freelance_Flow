import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = [
        "sidebar",
        "burger",
        "companyTopLeft",
        "companyNextBurger",
        "main",
        "topbarInner",
        "overlay",
        "userDropdown",
        "userChevron",
    ];

    connect() {
        this.sidebarOpenDesktop = true;
        this.initLayout();
        this.onResize = this.initLayout.bind(this);
        window.addEventListener("resize", this.onResize);
        console.log("Freelance Flow Layout Controller: Connected!");
    }

    disconnect() {
        window.removeEventListener("resize", this.onResize);
    }

    initLayout() {
        if (window.innerWidth >= 1024) {
            this.sidebarTarget.classList.remove("-translate-x-full");
            this.mainTarget.classList.add("lg:ml-64");
            this.topbarInnerTarget.classList.add("lg:ml-64");
            this.sidebarOpenDesktop = true;
        } else {
            this.sidebarTarget.classList.add("-translate-x-full");
            this.mainTarget.classList.remove("lg:ml-64");
            this.topbarInnerTarget.classList.remove("lg:ml-64");
            this.sidebarOpenDesktop = false;
            this.overlayTarget.classList.add("hidden");
        }
        this.applyCompanyVisibility();
    }

    toggleSidebar() {
        if (window.innerWidth < 1024) {
            const isHidden =
                this.sidebarTarget.classList.contains("-translate-x-full");
            this.sidebarTarget.classList.toggle("-translate-x-full");
            this.overlayTarget.classList.toggle("hidden", !isHidden);
        } else {
            this.sidebarOpenDesktop = !this.sidebarOpenDesktop;
            this.sidebarTarget.classList.toggle("-translate-x-full");
            this.mainTarget.classList.toggle("lg:ml-64");
            this.topbarInnerTarget.classList.toggle("lg:ml-64");

            this.applyCompanyVisibility();
        }
    }

    closeMobile() {
        this.sidebarTarget.classList.add("-translate-x-full");
        this.overlayTarget.classList.add("hidden");
    }

    toggleUserDropdown(event) {
        event.stopPropagation();
        this.userDropdownTarget.classList.toggle("hidden");
        this.userChevronTarget.classList.toggle("rotate-180");
    }

    closeUserDropdown() {
        this.userDropdownTarget.classList.add("hidden");
        this.userChevronTarget.classList.remove("rotate-180");
    }

    applyCompanyVisibility() {
        const isMobile = window.innerWidth < 1024;
        const target = this.companyNextBurgerTarget;

        if (isMobile) {
            this.companyTopLeftTarget.classList.add("hidden");
            target.classList.remove("hidden");
            requestAnimationFrame(() => {
                target.classList.remove("opacity-0", "-translate-x-4");
            });
        } else {
       
            this.companyTopLeftTarget.classList.remove("hidden");

            if (this.sidebarOpenDesktop) {
                this.companyTopLeftTarget.classList.remove("-translate-x-full");
                target.classList.add("opacity-0", "-translate-x-4");
                setTimeout(() => {
                        if (this.sidebarOpenDesktop) {
                        target.classList.add("hidden");
                    }
                }, 1000);
            } else {
                this.companyTopLeftTarget.classList.add("-translate-x-full");
                target.classList.remove("hidden");
                requestAnimationFrame(() => {
                    target.classList.remove("opacity-0", "-translate-x-4");
                });
            }
        }
    }
}
