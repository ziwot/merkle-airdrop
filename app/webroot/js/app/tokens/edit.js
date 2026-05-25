function handleMetadataSuccess(response) {
    const el = document.querySelector('.metadata');
    el.textContent = JSON.stringify(response.data.metadata, null, 4);
}

function handleMetadataError(error) {
    const el = document.querySelector('.metadata');
    el.style.borderColor = '#dc3545';
    el.style.boxShadow = '0 0 0 0.2rem rgba(220,53,69,0.25)';
    el.nextElementSibling?.remove?.();
    el.insertAdjacentHTML("afterend", `<div class="text-danger mt-2"><i class="bi bi-exclamation-triangle-fill"></i> ${error.message}</div>`);
}
