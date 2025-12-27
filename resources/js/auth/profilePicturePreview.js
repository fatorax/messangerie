document.addEventListener('DOMContentLoaded', function() {
    const imgPreview = document.querySelector('.profil-picture-preview');
    const fileInput = document.querySelector('.profile-picture-input');
    
    if (imgPreview && fileInput) {
        imgPreview.addEventListener('click', function() {
            fileInput.click();
        });
        
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    imgPreview.src = ev.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
