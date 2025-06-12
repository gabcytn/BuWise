document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.nav-dropdown').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const parent = this.closest('li');
            parent.classList.toggle('open');
        });
    });
});
