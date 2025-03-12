function copyLink() {
    const linkInput = document.getElementById('share-link');
    const link = linkInput.value;

    // Fallback support for older browsers
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(link).then(() => {
            showCopiedFeedback();
        });
    } else {
        // Legacy fallback
        linkInput.select();
        document.execCommand('copy');
        showCopiedFeedback();
    }
}

function showCopiedFeedback() {
    const copyBtn = document.getElementById('copy-btn');
    const originalText = copyBtn.textContent;
    copyBtn.textContent = 'Copied!';
    setTimeout(() => {
        copyBtn.textContent = originalText;
    }, 2000);
}

function shareInstagramGuide() {
    const linkInput = document.getElementById('share-link');
    const link = linkInput.value;

    // Copy link first
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(link).then(() => {
            document.getElementById("instagram-guide").style.display = "flex";
        });
    } else {
        linkInput.select();
        document.execCommand('copy');
        document.getElementById("instagram-guide").style.display = "flex";
    }
}

function closeGuide() {
    document.getElementById("instagram-guide").style.display = "none";
}