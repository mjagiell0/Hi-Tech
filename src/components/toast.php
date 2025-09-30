<!-- components/toast.php -->
<div id="toast-container"></div>

<style>
    #toast-container {
        position: fixed;
        top: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .toast {
        min-width: 220px;
        max-width: 300px;
        padding: 14px 20px;
        background-color: #333;
        color: #fff;
        font-size: 0.95rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        opacity: 0;
        transform: translateY(-20px);
        animation: toastFadeIn 0.4s ease forwards;
        transition: opacity 0.4s ease, transform 0.4s ease;
    }

    .toast.success { background-color: #4caf50; }
    .toast.error   { background-color: #f44336; }
    .toast.info    { background-color: #2196f3; }

    @keyframes toastFadeIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes toastFadeOut {
        to {
            opacity: 0;
            transform: translateY(-20px);
        }
    }

</style>

<script>
    function showToast(message, type = 'info', duration = 3000) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'toastFadeOut 0.4s ease forwards';
            setTimeout(() => toast.remove(), 400);
        }, duration);
    }
</script>
