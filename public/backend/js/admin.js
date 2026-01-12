(() => {
    const root = document.documentElement;

    // Theme restore
    const savedTheme = localStorage.getItem("admin_theme");
    if (savedTheme === "light") root.classList.add("light");

    // Sidebar toggle (mobile)
    const sidebar = document.querySelector(".sidebar");
    const btnHamburger = document.querySelector("[data-toggle-sidebar]");
    const btnCloseSidebar = document.querySelector("[data-close-sidebar]");
    const sidebarBackdrop = document.querySelector("[data-sidebar-backdrop]");

    function openSidebar() {
        sidebar?.classList.add("open");
        if (sidebarBackdrop) sidebarBackdrop.style.display = "block";
    }
    function closeSidebar() {
        sidebar?.classList.remove("open");
        if (sidebarBackdrop) sidebarBackdrop.style.display = "none";
    }

    btnHamburger?.addEventListener("click", openSidebar);
    btnCloseSidebar?.addEventListener("click", closeSidebar);
    sidebarBackdrop?.addEventListener("click", closeSidebar);

    // Theme toggle
    const btnTheme = document.querySelector("[data-toggle-theme]");
    btnTheme?.addEventListener("click", () => {
        root.classList.toggle("light");
        localStorage.setItem(
            "admin_theme",
            root.classList.contains("light") ? "light" : "dark"
        );
    });

    // Active nav (based on pathname)
    const path = window.location.pathname.replace(/\/+$/, "");
    document.querySelectorAll(".nav a").forEach((a) => {
        const href = (a.getAttribute("href") || "").replace(/\/+$/, "");
        if (!href) return;
        if (
            path.endsWith(href) ||
            (href.endsWith("index.html") && path.endsWith("/admin"))
        ) {
            a.classList.add("active");
        }
    });

    // Table search
    const searchInput = document.querySelector("[data-table-search]");
    const rows = Array.from(document.querySelectorAll("tbody tr"));
    searchInput?.addEventListener("input", (e) => {
        const q = (e.target.value || "").toLowerCase().trim();
        rows.forEach((tr) => {
            const text = tr.innerText.toLowerCase();
            tr.style.display = text.includes(q) ? "" : "none";
        });
    });

    // Filter status
    const statusSelect = document.querySelector("[data-filter-status]");
    statusSelect?.addEventListener("change", (e) => {
        const v = (e.target.value || "").toLowerCase();
        rows.forEach((tr) => {
            const status = (tr.getAttribute("data-status") || "").toLowerCase();
            if (!v || v === "all") tr.style.display = "";
            else tr.style.display = status === v ? "" : "none";
        });
    });

    // Modal (detail pendaftar)
    const modalBackdrop = document.querySelector(".modal-backdrop");
    const modalTitle = document.querySelector("[data-modal-title]");
    const modalBody = document.querySelector("[data-modal-body]");

    function openModal({ title, body }) {
        if (modalTitle) modalTitle.textContent = title || "Detail";
        if (modalBody) modalBody.innerHTML = body || "";
        if (modalBackdrop) modalBackdrop.style.display = "flex";
    }
    function closeModal() {
        if (modalBackdrop) modalBackdrop.style.display = "none";
    }

    document.querySelectorAll("[data-open-detail]").forEach((btn) => {
        btn.addEventListener("click", () => {
            const name = btn.getAttribute("data-name") || "Calon Santri";
            const nisn = btn.getAttribute("data-nisn") || "-";
            const status = btn.getAttribute("data-status") || "-";
            const phone = btn.getAttribute("data-phone") || "-";
            const wave = btn.getAttribute("data-wave") || "Gelombang 1";
            openModal({
                title: `Detail Pendaftar — ${name}`,
                body: `
          <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
            <div><b>Nama</b><br>${name}</div>
            <div><b>NISN</b><br>${nisn}</div>
            <div><b>Status</b><br>${status}</div>
            <div><b>HP/WA</b><br>${phone}</div>
            <div><b>Periode</b><br>${wave}</div>
            <div><b>Catatan</b><br><span style="color:var(--muted)">Belum ada catatan.</span></div>
          </div>
          <hr style="border:0;border-top:1px solid var(--border);margin:12px 0;">
          <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button class="btn primary" type="button">Tandai Verified</button>
            <button class="btn" type="button">Minta Revisi</button>
            <button class="btn danger" type="button">Tolak</button>
          </div>
        `,
            });
        });
    });

    document.querySelectorAll("[data-close-modal]").forEach((btn) => {
        btn.addEventListener("click", closeModal);
    });
    modalBackdrop?.addEventListener("click", (e) => {
        if (e.target === modalBackdrop) closeModal();
    });

    // Keyboard shortcuts
    window.addEventListener("keydown", (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "k") {
            e.preventDefault();
            document.querySelector(".search input")?.focus();
        }
        if (e.key === "Escape") {
            closeModal();
            closeSidebar();
        }
    });
})();
