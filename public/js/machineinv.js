document.querySelectorAll('[data-dropdown-toggle]').forEach(button => {
    button.addEventListener('click', function() {
        const dropdownId = this.getAttribute('data-dropdown-toggle');
        const dropdown = document.getElementById(dropdownId);
        const additionalInfo = document.getElementById('additionalInfo');
        dropdown.classList.toggle('hidden');
        additionalInfo.classList.toggle('hidden');
    });
});

function toggleContent(contentId) {
    const content = document.getElementById(contentId);
    content.classList.toggle('hidden');
}

document.querySelectorAll('.toggle-info').forEach(button => {
    button.addEventListener('click', function() {
        const additionalInfo = this.closest('.machine-entry').querySelector('.additional-info');
        additionalInfo.classList.toggle('hidden');
    });
});