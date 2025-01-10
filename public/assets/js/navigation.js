// Gestion de l'état actif des liens de navigation
const currentPath = window.location.pathname;
document.querySelectorAll('.nav-link').forEach(link => {
    if (link.getAttribute('href') === currentPath) {
        link.classList.add('active');
        // Si le lien est dans un sous-menu, ouvrir le sous-menu
        const submenu = link.closest('.collapse');
        if (submenu) {
            submenu.classList.add('show');
            const parentToggle = document.querySelector(`[data-bs-toggle="collapse"][href="#${submenu.id}"]`);
            if (parentToggle) {
                parentToggle.setAttribute('aria-expanded', 'true');
            }
        }
    }
});