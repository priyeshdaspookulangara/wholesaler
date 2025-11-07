document.addEventListener("DOMContentLoaded", function(event) {

    const menuToggle = document.body.querySelector('#menu-toggle');
    menuToggle.addEventListener('click', event => {
        event.preventDefault();
        document.body.classList.toggle('sb-sidenav-toggled');
        localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));

        const wrapper = document.getElementById('wrapper');
        wrapper.classList.toggle('toggled');
    });
});
